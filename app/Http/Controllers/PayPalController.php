<?php

namespace App\Http\Controllers;

use App\Http\Requests\register;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\UserPaymentIntent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Str;
use App\Http\Requests\Paypal\Plan\createPlan;
use Carbon\Carbon;

class PayPalController extends Controller
{
    private $paypalToken;

    public function __construct()
    {
        $paypalCredentials = [
            'url' => config('utility.paypal_authentication_api'),
            'method' => config('utility.method_post'),
            'postField' => [
                'grant_type' => 'client_credentials',
                'ignoreCache' => 'true',
                'return_authn_schemes' => 'true',
                'return_unconsented_scopes' => 'true',
            ]
        ];
        $header = array(
            'Authorization: Basic ' . config('utility.Authentication'),
            'Content-Type: application/x-www-form-urlencoded'
        );
        $response = $this->fireCURL($paypalCredentials, http_build_query($paypalCredentials['postField']), $header);
        $this->paypalToken = $response->access_token;
    }

    public function checkoutView()
    {
        return view('paypal.checkout');
    }

    public function OrderCapture(Request $request)
    {
        UserPaymentIntent::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'payment_capture_id' => $request->payment_capture_id,
                'amount' => $request->amount
            ]
        );
    }

    public function PayPalProductList()
    {
        $products = Product::where('is_product', 'paypal')->get();
        return view('paypal.product.listing', compact('products'));
    }

    public function createProduct()
    {
        return view('paypal.product.create');
    }

    public function storeProduct(Request $request)
    {
        try {
            DB::beginTransaction();

            $paypalCredentials = [
                'url' => Config('utility.paypal_create_product_endpoint'),
                'postField' => '{
                    "name":"'  . $request->productname . '",
                    "description":"' . $request->description . '",
                    "type":"' . $request->producttype . '",
                    "category":"' . strtoupper($request->category) . '",
                    "image_url": "https://example.com/streaming.jpg",
                    "home_url": "https://example.com/home"
                  }',
                'method' => config('utility.method_post'),
            ];
            $header = array(
                'Content-Type: application/json',
                'PayPal-Request-Id: PRODUCT-ID-' . time(),
                'Authorization: Bearer ' . $this->paypalToken,
            );
            $createProductResponse = $this->fireCURL($paypalCredentials, $paypalCredentials['postField'], $header);
            if ($createProductResponse) {
                Product::create([
                    'product_id' => $createProductResponse->id,
                    'product_name' => $createProductResponse->name,
                    'description' => $createProductResponse->description,
                    'is_product' => 'paypal'
                ]);
                DB::commit();
            }
            flash('Your paypal product is created sucessfully.')->success();
            return redirect()->route('paypal-product-list');
        } catch (Exception $e) {
            DB::rollback();
            Log::info("General exception occure" . $e->getMessage());
            flash('Something went to wrong, Plesase try again')->error();
            return redirect()->back();
        }
    }

    public function cardView()
    {
        return view('paypal.card');
    }

    public function createPlan($productId)
    {
        // $products = Product::where('is_paypal_product','yes')->findOrFail($productId);   
        return view('paypal.plan.create', compact('productId'));
    }

    public function storePlan(Request $request)
    {

        try {
            DB::beginTransaction();

            $product = Product::where('is_product', 'paypal')->findOrFail($request->productid);
            $billing_cycle = [];
            $productdefinitionname = $request->productdefinitionname;
            $planprice = $request->planprice;
            $planfrequency = $request->planfrequency;

            $sequence = 1;
            foreach ($request->plantype as $key => $value) {
                if (array_count_values($request->plantype)[$value] > 1) {
                    return redirect()->back()->with('message', 'Please enter billing period values is one time.');
                }
                $billing_cycle[$key]['name'] = $productdefinitionname[$key];
                $billing_cycle[$key]['frequency']['interval_unit'] = strtoupper($planfrequency[$key]);
                $billing_cycle[$key]['frequency']['interval_count'] = 1;
                $billing_cycle[$key]['tenure_type'] = strtoupper($value);
                $billing_cycle[$key]['sequence'] = $sequence;
                $billing_cycle[$key]['total_cycles'] = 1;
                $billing_cycle[$key]['pricing_scheme']['fixed_price']['value'] = $planprice[$key];
                $billing_cycle[$key]['pricing_scheme']['fixed_price']['currency_code'] = 'USD';
                $sequence = $sequence + 1;
            }

            $paypalCredentials = [
                'url' => Config('utility.paypal_create_plan_endpoint'),
                'method' => config('utility.method_post'),
                'postField' => '{
                "product_id": "' . $product->product_id . '",
                "name": "' . $request->planname . '",
                "description": "' . $request->description . '",
                "status": "ACTIVE",
                "billing_cycles": ' . json_encode($billing_cycle) . '
                ,
                "payment_preferences": {
                  "auto_bill_outstanding": true,
                  "setup_fee": {
                    "value": "10",
                    "currency_code": "USD"
                  },
                  "setup_fee_failure_action": "CONTINUE",
                  "payment_failure_threshold": 3
                },
                "taxes": {
                  "percentage":"' . $request->tax . '",
                  "inclusive": false
                }
              }'
            ];

            $header = array(
                'Content-Type: application/json',
                'PayPal-Request-Id: CREATE-PLAN-' . time(),
                'Prefer: return=representation',
                'Authorization: Bearer ' . $this->paypalToken
            );

            $createPlan = $this->fireCURL($paypalCredentials, $paypalCredentials['postField'], $header);
            $product = Product::create([
                'product_id' => $product->product_id,
                'plan_id' => $createPlan->id,
                'product_name' =>  $createPlan->name,
                'description' => $createPlan->description,
                'status' => $createPlan->status,
                'is_plan' => 'paypal'
            ]);
            DB::commit();
            flash('Your paypal plan is created sucessfully.')->success();
            return redirect()->route('paypal-plan-list');
        } catch (Exception $e) {
            DB::rollback();
            Log::info("General exception occure" . $e->getMessage());
            flash('Something went to wrong, Plesase try again')->error();
            return redirect()->back();
        }
    }

    public function editProduct($productId)
    {
        $products = Product::where('is_product', 'paypal')->findOrFail($productId);
        return view('paypal.product.edit', compact('products'));
    }

    public function planList()
    {
        $plans = Product::where('is_plan', 'paypal')->get();
        return view('paypal.plan.list', compact('plans'));
    }

    public function createSubscription($planId)
    {
        $product = Product::where('is_plan', 'paypal')->findOrFail($planId);
        $user = auth()->user();
        return view('paypal.subscription.create', compact('product', 'user'));
    }

    public function storeSubscription(Request $request)
    {
        DB::beginTransaction();
        try {
            $subscription = Subscription::create([
                'user_id' => Auth::id(),
                'subscription_id' => $request->subscriptionId,
                'is_subscription' => 'paypal',
            ]);
            DB::commit();
            if ($subscription) {
                return true;
            }
        } catch (Exception $e) {
            DB::rollback();
            Log::info("General exception occure" . $e->getMessage());
            return false;
        }
    }

    public function editPlan($planId)
    {
        $plan = Product::where('is_paypal_plan', 'yes')->where('plan_id', $planId)->firstOrFail();
        // return view('');
    }

    public function subscritpionActivatDeactivate($subscriptionId)
    {
        $userSubscription = Subscription::findOrFail($subscriptionId);

        if ($userSubscription->status == 'activated') {
            $action = '/suspend';
            $reason = 'Customer-requested pause';
        } else {
            $action = '/activate';
            $reason = 'Reactivating on customer request';
        }
        $paypalCredentials = [
            'url' => 'https://api-m.sandbox.paypal.com/v1/billing/subscriptions/' . $userSubscription->subscription_id . $action,
            'postField' => '{
                "reason":"' . $reason . '" 
              }',
            'method' => config('utility.method_post'),
        ];
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->paypalToken,
        );
        if ($userSubscription) {
            if ($userSubscription->status == 'activated') {
                $this->fireCURL($paypalCredentials, $paypalCredentials['postField'], $header);
                $userSubscription->status = 'deactivate';
                $userSubscription->save();
                flash('Your paypal subscription is pause sucessfully.')->success();
                return redirect()->back();
            } else {
                $this->fireCURL($paypalCredentials, $paypalCredentials['postField'], $header);
                $userSubscription->status = 'activated';
                $userSubscription->save();
                flash('Your paypal subscription is resume sucessfully.')->success();
                return redirect()->back();
            }
        }
    }
}

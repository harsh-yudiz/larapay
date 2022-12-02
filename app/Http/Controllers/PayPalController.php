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

class PayPalController extends Controller
{
    private $payPalProvider;
    public function __construct()
    {
        //Paypal access token.
        $this->payPalProvider = new PayPalClient;
        $this->payPalProvider->getAccessToken();
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
            $data =  json_decode('{
                "name":"'  . $request->productname . '",
                "description":"' . $request->description . '",
                "type": "SERVICE",
                "category":"' . strtoupper($request->category) . '",
                "image_url": "https://example.com/streaming.jpg",
                "home_url": "https://example.com/home"
              }', true);

            $request_id = 'create-product-' . time();

            $createProductResponse = $this->payPalProvider->createProduct($data, $request_id);
            if ($createProductResponse) {
                $product = Product::create([
                    'product_id' => $createProductResponse['id'],
                    'product_name' => $createProductResponse['name'],
                    'description' => $createProductResponse['description'],
                    'is_product' => 'paypal'
                ]);
                DB::commit();
            }
            return redirect()->route('paypal-product-list');
        } catch (Exception $e) {
            DB::rollback();
            Log::info("General exception occure" . $e->getMessage());
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
        $product = Product::where('is_product', 'paypal')->findOrFail($request->productid);
        $billing_cycle = [];
        $productdefinitionname = $request->productdefinitionname;
        $planprice = $request->planprice;
        $planfrequency = $request->planfrequency;

        foreach ($request->billingperiod as $key => $value) {
            if (array_count_values($request->billingperiod)[$value] > 1) {
                return redirect()->back()->with('message', 'Please enter billing period values is one time.');
            }
            $billing_cycle[$key]['name'] = $productdefinitionname[$key];
            $billing_cycle[$key]['frequency']['interval_unit'] = strtoupper($planfrequency[$key]);
            $billing_cycle[$key]['frequency']['interval_count'] = 1;
            $billing_cycle[$key]['tenure_type'] = strtoupper($value);
            $billing_cycle[$key]['sequence'] = 1;
            $billing_cycle[$key]['total_cycles'] = 0;
            $billing_cycle[$key]['pricing_scheme']['fixed_price']['value'] = $planprice[$key];
            $billing_cycle[$key]['pricing_scheme']['fixed_price']['currency_code'] = 'USD';
        }

        $data = json_decode('{
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
              "percentage": "10",
              "inclusive": false
            }
          }', true);

        $request_id = 'create-plan-' . time();

        $createPlan = $this->payPalProvider->createPlan($data, $request_id);

        $product = Product::create([
            'product_id' => $product->product_id,
            'plan_id' => $createPlan['id'],
            'product_name' =>  $createPlan['name'],
            'description' => $createPlan['description'],
            'status' => $createPlan['status'],
            'is_plan' => 'paypal'
        ]);
        return redirect()->route('paypal-plan-list');
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
}

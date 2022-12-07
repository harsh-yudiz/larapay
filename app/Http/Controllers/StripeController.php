<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreast;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPaymentIntent;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(
            config('services.stripe.secret')
        );
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function card()
    {
        $user = User::with('paymentIntents')->where('id', auth()->id())->first();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'payment_intent_secret' => $user->paymentIntents->payment_intent_secret,
        ];
        return view('stripe.card', compact('data'));
    }

    public function CheckoutView()
    {
        return view('stripe.checkout');
    }

    public function createPaymentIntent(Request $request)
    {
        //create stipe payment intent
        $intent = \Stripe\PaymentIntent::create([
            'amount' => ($request->amount) * 100,
            'currency' => 'INR',
            'metadata' => [
                'integration_check' => 'accept_a_payment',
                'amount' => $request->amount
            ]
        ]);

        if ($intent) {
            UserPaymentIntent::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'payment_intent_id' => $intent->id,
                    'payment_intent_secret' => $intent->client_secret,
                    'amount' => ($request->amount) * 100
                ]
            );
            return redirect()->route('card');
        }
    }

    public function createProduct()
    {
        $currencys = DB::table('currencys')->get();
        return view('stripe.product.createProduct', compact('currencys'));
    }

    public function storeProduct(ProductCreast $request)
    {
        try {
            DB::beginTransaction();
            $sripeProduct = $this->stripe->products->create([
                'name'      => $request->productname,
                'default_price_data' => [
                    'unit_amount' => $request->price * 100,
                    'currency' => $request->currency,
                    'recurring' => [
                        'interval' => $request->billingperiod,
                        'interval_count' => 1,
                    ]
                ]
            ]);
            if ($sripeProduct) {
                $price =  $this->stripe->prices->retrieve(
                    $sripeProduct->default_price,
                    []
                );
                if ($price) {
                    if ($sripeProduct->active == false) {
                        $status = 'deactivate';
                    } else {
                        $status = 'activate';
                    }
                    $product = Product::create([
                        'product_id' => $sripeProduct->id,
                        'price_id' => $sripeProduct->default_price,
                        'product_name' => $sripeProduct->name,
                        'description' => $request->description,
                        'billing_period' => $request->billingperiod,
                        'product_price' =>   $price->unit_amount_decimal / 100,
                        'is_product' => 'stripe',
                        'status' => $status
                    ]);

                    DB::commit();
                    if ($product) {
                        flash('Your product is created sucessfully.')->success();
                        return redirect()->route('stripe-product-list');
                    }
                }
            }
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            DB::rollback();
            Log::info("Api Connetion Exception occure" . $e->getMessage());
            flash('Something went to wrong, please try again.')->error();
            return redirect()->back();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            DB::rollback();
            flash('Something went to wrong, please try again.')->error();
            Log::info("Api Error Exception occure " . $e->getMessage());
            return redirect()->back();
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            flash('Something went to wrong, please try again.')->error();
            Log::info("Model not found exception occure" . $e->getMessage());
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            flash('Something went to wrong, please try again.')->error();
            Log::info("General exception occure" . $e->getMessage());
            return redirect()->back();
        }
    }

    public function editProduct($productId)
    {
        $product = Product::findOrFail($productId);
        return view('stripe.product.edit', compact('product'));
    }

    public function updateProduct(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        if ($product) {
            $updateProduct = $this->stripe->products->update(
                $product->product_id,
                [
                    'description' => $request->description,
                    'active' => $request->status,
                ]
            );
            if ($updateProduct) {
                if ($updateProduct->active == false) {
                    $product->status = 'deactivate';
                } else {
                    $product->status = 'activate';
                }
                $product->description = $updateProduct->description;
                $product->save();
                flash('your Product updated sucessfully.')->success();
                return redirect()->route('stripe-product-list');
            }
        }
    }

    public function productList()
    {
        $products = Product::where('is_product', 'stripe')->get();
        return view('stripe.product.productList', compact('products'));
    }

    public function Subscription($productId)
    {
        return view('stripe.subscriptionCard', compact('productId'));
    }

    public function createSubscription(Request $request)
    {
        try {
            $date = null;
            $product = Product::select('price_id', 'id')->findOrFail($request->prodId);
            $user = auth()->user();
            $subscription = Subscription::where('user_id', $user->id)->where('is_subscription', 'stripe')->first();
            if (!$user->stripe_customer_id) {
                $stripeCustomer = $this->stripe->customers->create([
                    [
                        'email'     =>      $user->email,
                        'name'      =>      $user->name,
                        'address' => [
                            'line1' => '510 Townsend St',
                            'postal_code' => '98140',
                            'city' => 'San Francisco',
                            'state' => 'CA',
                            'country' => 'US',
                        ],
                    ]
                ]);
                $user->stripe_customer_id = $stripeCustomer->id;
                $user->save();
            }


            if ($subscription != null && $subscription->subscription_id != null) {
                $stripeRetriveSubscription = $this->stripe->subscriptions->retrieve(
                    $subscription->subscription_id
                );
                $stripeRetriveSubscription->status == 'active' ? $date = $stripeRetriveSubscription->current_period_end  : $date  = "now";
            } else {
                $date = "now";
            }

            $this->stripe->customers->createSource(
                $user->stripe_customer_id,
                ["source" => $request->stripe_token]
            );

            // $subscriptionScheduled = $this->stripe->subscriptions->create([
            //     'customer' => $user->stripe_customer_id,
            //     'items' => [
            //         ['price' => $product->price_id],
            //     ],
            // ]);


            $subscriptionScheduled = \Stripe\SubscriptionSchedule::create([
                "customer" => $user->stripe_customer_id,
                "start_date" => $date,
                "end_behavior" => "release",
                "phases" => [
                    [
                        "items" => [
                            [
                                "price" => $product->price_id,
                                "quantity" => 1,
                            ],
                        ],
                        "iterations" => 12
                    ],
                ],
            ]);

            $subscription = Subscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'subscription_id' => $subscriptionScheduled->status == 'active' ? $subscriptionScheduled->id : $subscription->subscription_id,
                    'schedule_subscription_id' => $subscriptionScheduled->status == 'not_started' ? $subscriptionScheduled->id : null,
                    'scheduled_period_start' => $subscriptionScheduled->status == 'not_started' ? Carbon::createFromTimestamp($subscriptionScheduled['phases'][0]['start_date'])->format('Y-m-d') : null,
                    'stripe_customer_id' => $user->stripe_customer_id,
                    'plan_id' => $product->id,
                    'status' => 'deactivate',
                    'is_subscription' => 'stripe'
                ]
            );
            DB::commit();

            if ($subscription) {
                flash('Your subscription created sucessfully.')->success();
                return redirect()->route('user-listing');
            }
        } catch (\Stripe\Exception\CardException $e) {
            DB::rollback();
            Log::info("card exception" . $e->getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $invalidRequestException) {
            DB::rollback();
            Log::info("Invalid Request Exception occure." . $invalidRequestException->getMessage());
        } catch (\Stripe\Exception\AuthenticationException $AuthenticationException) {
            DB::rollback();
            Log::info("Authentication Exception occure." . $AuthenticationException->getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $ApiConnnectionExcepiton) {
            DB::rollback();
            Log::info("Api Connection Exception occurred." . $ApiConnnectionExcepiton->getMessage());
        } catch (\Stripe\Exception\ApiErrorException $ApiErrorException) {
            DB::rollback();
            Log::info("Api Error Exception occurred." . $ApiErrorException->getMessage());
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            Log::info("Model not found exception occure" . $e->getMessage());
        } catch (Exception $e) {
            DB::rollback();
            Log::info("General exception occure" . $e->getMessage());
        }
        flash('Something went to wrong, please try again.')->error();
        return redirect()->back();
    }

    public function cancelSubscription($subscriptionid)
    {
        try {
            DB::beginTransaction();
            $subscription = Subscription::findOrFail($subscriptionid);
            $stripeRetriveSubscription = $this->stripe->subscriptions->retrieve(
                $subscription->subscription_id
            );
            if ($stripeRetriveSubscription) {
                $subscriptionCancelResponse = $this->stripe->subscriptions->cancel(
                    $subscription->subscription_id,
                );
                if ($subscriptionCancelResponse->status == 'canceled') {
                    $subscription->status = $subscriptionCancelResponse->status;
                    $subscription->save();
                    DB::commit();
                    flash('Your subscription is canceled sucessfully.')->success();
                    return redirect()->back();
                }
            }
        } catch (\Stripe\Exception\CardException $e) {
            DB::rollback();
            Log::info("card exception" . $e->getMessage());
        } catch (\Stripe\Exception\InvalidRequestException $invalidRequestException) {
            DB::rollback();
            Log::info("Invalid Request Exception occure." . $invalidRequestException->getMessage());
        } catch (\Stripe\Exception\AuthenticationException $AuthenticationException) {
            DB::rollback();
            Log::info("Authentication Exception occure." . $AuthenticationException->getMessage());
        } catch (\Stripe\Exception\ApiConnectionException $ApiConnnectionExcepiton) {
            DB::rollback();
            Log::info("Api Connection Exception occurred." . $ApiConnnectionExcepiton->getMessage());
        } catch (\Stripe\Exception\ApiErrorException $ApiErrorException) {
            DB::rollback();
            Log::info("Api Error Exception occurred." . $ApiErrorException->getMessage());
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            Log::info("Model not found exception occure" . $e->getMessage());
        } catch (Exception $e) {
            DB::rollback();
            Log::info("General exception occure" . $e->getMessage());
        }
        flash('Something went to wrong, please try again.')->error();
        return redirect()->back();
    }

    //Dont use
    public function createPrice()
    {
        $stripeProduct = $this->stripe->prices->create([
            'unit_amount' => 150,
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'product' => 'prod_MuK06kmZdqwhPd',
        ]);
        dd($stripeProduct);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        if ($product) {
            $stripeProduct = $this->stripe->products->delete(
                $product->product_id,
                []
            );
            if ($stripeProduct) {
                $product->delete();
                flash('Your product is deleted is sucessfully.')->success();
                return redirect()->back();
            }
        }
    }

    public function SubscriptionActiveDeactive($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);
        $retriveSubscription = $this->stripe->subscriptions->retrieve(
            $subscription->subscription_id,
            []
        );
        if ($retriveSubscription->status == 'active') {
            if ($retriveSubscription->pause_collection == null) {
                $pauseCollection = $this->stripe->subscriptions->update(
                    $retriveSubscription->id,
                    ['pause_collection' => ['behavior' => 'void']]
                );
                if ($pauseCollection->pause_collection->behavior == 'void') {
                    $subscription->status   = 'deactive';
                    $subscription->save();
                    flash('Your subscription collection is push sucessfully.')->success();
                    return redirect()->back();
                }
            } else {
                $pauseCollection =  $this->stripe->subscriptions->update(
                    $subscription->subscription_id,
                    [
                        'pause_collection' => '',
                    ]
                );
                if ($pauseCollection->pause_collection == null) {
                    $subscription->status   = 'activated';
                    $subscription->save();
                    flash('Your subscription collection is resume sucessfully.')->success();
                    return redirect()->back();
                }
            }
        }
    }
}

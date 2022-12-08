<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Stripe\Stripe;
use App\Models\UserPaymentIntent;
use Carbon\Carbon;
use Stripe\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(
            config('services.stripe.secret')
        );

        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function StripeAction(Request $request)
    {
        $event = Event::retrieve($request->id);
        $userPaymentIntent = UserPaymentIntent::with('user')->where('payment_intent_id', $event->data->object->payment_intent)->first();
        $userSubscription = Subscription::with('user')->where('stripe_customer_id', $event->data->object->customer)->first();
        if ($userPaymentIntent != null && $userPaymentIntent->user != null) {
            $user_id = $userPaymentIntent->user['id'];
        } else {
            $user_id = $userSubscription->user['id'];
        }

        if ($event->type == 'charge.succeeded') {
            $payment = Payment::create([
                'user_id' => $user_id,
                'charge_event_id' => $event->data->object->id,
                'payment_type' => $event->data->object->payment_method_details->type,
                'payment_intent_id' => $event->data->object->payment_intent,
                'amount' => $event->data->object->amount / 100,
                'status' => $event->data->object->status,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
            if ($payment && $event['data']['object']['invoice'] != null && $userSubscription) {
                if ($payment) {
                    $SubscriptionCreatedDate = date('Y-m-d');
                    $SubscriptionEndDate = date('Y-m-d', strtotime($SubscriptionCreatedDate . ' + 30 days'));
                    $userSubscription->current_period_end = $SubscriptionEndDate;
                    $userSubscription->current_period_start = $SubscriptionCreatedDate;
                    $userSubscription->status = 'activated';
                    if ($userSubscription->schedule_subscription_id != null && $userSubscription->current_period_end != null && $userSubscription->current_period_end == Carbon::now()->toDateString()) {
                        $userSubscription->subscription_id = $userSubscription->schedule_subscription_id;
                        $userSubscription->schedule_subscription_id = null;
                    }
                    $userSubscription->save();
                    Log::info('---------- Subscritpion Payment Create ----------');
                }
            }

            Log::info('---------- Stripe Payment Create ----------');
            Log::info(json_encode($event));
        }

        if ($event->type == 'charge.failed') {
            $paymentFaild = new Payment();
            $paymentFaild->status = 'failed';
            $paymentFaild->user_id = $userPaymentIntent->user->id;
            $paymentFaild->amount = 0.00;
            $paymentFaild->created_at = \Carbon\Carbon::now();
            $paymentFaild->updated_at = \Carbon\Carbon::now();
            $paymentFaild->save();
            Log::info('---------- Stripe Payment Faild ----------');
            Log::info(json_encode($event));
        }
    }

    public function PaypalPaymentIntentAction(Request $request)
    {
        $event = $request->all();
        $userPaymentIntent = USerPaymentIntent::with('user')->where('payment_capture_id', $event['resource']['id'])->first();
        if ($event['event_type'] == 'CHECKOUT.ORDER.APPROVED') {
            Payment::create([
                'user_id' => $userPaymentIntent->user->id,
                'charge_event_id' => $event['id'],
                'amount' => $event['resource']['amount']['value'],
                'status' => $event['resource']['status'],
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
            Log::info('---------- PayPal Payment Create ----------');
            Log::info(json_encode($event));
        }

        $subscription = Subscription::with('user')->where('subscription_id', $event['resource']['id'])->first();

        if ($event['event_type'] == 'BILLING.SUBSCRIPTION.ACTIVATED') {
            if (!isset($event['resource']['status_change_note']) || !empty($event['resource']['status_change_note'])) {
                if (!empty($subscription) || $subscription != null) {
                    $payment = Payment::create([
                        'user_id' => $subscription->user ? $subscription->user['id'] : null,
                        'charge_event_id' => $event['id'],
                        'payment_intent_id' => $event['resource']['subscriber']['payer_id'],
                        'amount' => $event['resource']['billing_info']['last_payment']['amount']['value'],
                        'status' => 'Activate',
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                    ]);

                    if ($payment) {
                        $SubscriptionCreatedDate = date('Y-m-d');
                        $SubscriptionEndDate = date('Y-m-d', strtotime($SubscriptionCreatedDate . ' + 30 days'));
                        $subscription->current_period_end = $SubscriptionEndDate;
                        $subscription->current_period_start = $SubscriptionCreatedDate;
                        $subscription->status = 'activate';
                        $subscription->save();
                        Log::info('---------- Payment Create ----------');
                    }
                }
            }
        }

        if ($event['event_type'] == 'BILLING.SUBSCRIPTION.PAYMENT.FAILD') {
            $paymentFaild = new Payment();
            $paymentFaild->payment_status = 'failed';
            $paymentFaild->user_id = $subscription->user ? $subscription->user['id'] : null;
            $paymentFaild->amount = 0.00;
            $paymentFaild->save();
        }
    }
}

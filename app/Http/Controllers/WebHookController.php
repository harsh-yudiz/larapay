<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Stripe\Stripe;
use App\Models\UserPaymentIntent;
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

    public function paymentIntentAction(Request $request)
    {
        $event = Event::retrieve($request->id);
        $userPaymentIntent = UserPaymentIntent::with('user')->where('payment_intent_id', $event->data->object->payment_intent)->first();
        if ($event->type == 'charge.succeeded') {
            Payment::create([
                'user_id' => $userPaymentIntent->user->id,
                'charge_event_id' => $event->data->object->id,
                'payment_type' => $event->data->object->payment_method_details->type,
                'payment_intent_id' => $event->data->object->payment_intent,
                'amount' => $userPaymentIntent->amount,
                'status' => $event->data->object->status,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
            Log::info('---------- Payment Create ----------');
        }
        if ($event->type == 'charge.failed') {
            $paymentFaild = new Payment();
            $paymentFaild->payment_status = 'failed';
            $paymentFaild->user_id = $userPaymentIntent->user->id;
            $paymentFaild->amount = 0.00;
            $paymentFaild->created_at = \Carbon\Carbon::now();
            $paymentFaild->updated_at = \Carbon\Carbon::now();
            $paymentFaild->save();
            Log::info('---------- Payment Faild ----------');
        }

        Log::info(json_encode($event));
    }
}

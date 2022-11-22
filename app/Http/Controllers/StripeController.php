<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPaymentIntent;
use Illuminate\Support\Facades\Auth;
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
        $user = User::with('paymentIntents')->first();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'payment_intent_secret' => $user->paymentIntents->payment_intent_secret,
        ];
        return view('card', compact('data'));
    }

    public function CheckoutView()
    {
        return view('checkout');
    }

    public function checkout(Request $request)
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

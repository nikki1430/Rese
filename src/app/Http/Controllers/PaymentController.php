<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $user = Auth::user();

        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        Charge::create([
            'amount' => 100,
            'currency' => 'jpy',
            'source' => $request->stripeToken,
        ]);

        $user->update([
            'stripe_id' => $user->stripe_id,
        ]);

        return back();
    }
}

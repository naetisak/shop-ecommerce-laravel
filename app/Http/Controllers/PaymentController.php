<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Calculate total amount from cart items
        $items = $request->input('items');
        $totalAmount = 0;
        foreach ($items as $item) {
            // Assuming each item has a 'price' attribute
            $totalAmount += $item['price'];
        }

        // Create PaymentIntent
        $intent = PaymentIntent::create([
            'amount' => $totalAmount * 100, // Stripe requires amount in cents
            'currency' => 'usd', // Change this to your currency
        ]);

        return response()->json(['client_secret' => $intent->client_secret]);
    }
}

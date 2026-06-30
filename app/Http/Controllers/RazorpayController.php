<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api as RazorpayApi;

class RazorpayController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $razorpay = new RazorpayApi;
        $razorpay->setApiKey(config('razorpay.key_secret'));

        $order = $razorpay->order->create([
            'amount'   => (int) ($request->amount * 100), // Razorpay expects paise
            'currency' => 'INR',
            'receipt'  => 'jem_' . uniqid(),
        ]);

        return response()->json([
            'id'       => $order->id,
            'amount'   => $order->amount,
            'currency' => $order->currency,
        ]);
    }
}

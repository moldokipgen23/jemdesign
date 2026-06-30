<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'cart'            => 'required|array|min:1',
            'customer_name'   => 'nullable|string|max:200',
            'customer_phone'  => 'nullable|string|max:30',
        ]);

        $items = collect($data['cart']);
        $total = $items->sum(fn($item) => ($item['price'] ?? 0) * ($item['qty'] ?? 1));

        Inquiry::create([
            'customer_name'  => $data['customer_name'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'cart_summary'   => $data['cart'],
            'total_estimate' => $total,
            'status'         => 'new',
        ]);

        return response()->json(['ok' => true], 201);
    }
}

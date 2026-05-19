<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // LOG semua request dari payment gateway
        Log::info('PAYMENT CALLBACK', $request->all());

        // 🔐 PIN VALIDATION (kalau ada PIN dari gateway)
        $pin = $request->pin ?? null;

        if ($pin !== "SkillUpit2026Secure") {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid PIN'
            ], 403);
        }

        // 🧠 contoh data
        $orderId = $request->order_id;
        $status  = $request->status;

        // TODO: update database transaksi kamu
        // contoh:
        // Order::where('order_id', $orderId)->update(['status' => $status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Callback received'
        ]);
    }
}

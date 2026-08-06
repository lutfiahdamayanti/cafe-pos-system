<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function tracking()
    {
        $order = Order::latest()->first();
        if (!$order) {
            return redirect()->route('home')
                ->with('error','Belum ada pesanan.');
        }
        return view('customer.tracking', compact('order'));
    }

    public function status($id)
    {
        $order = Order::findOrFail($id);
        return response()->json([
            'status' => $order->status
        ]);
    }
}
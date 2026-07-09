<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;


class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::with('details.menu')
            ->whereIn('status', [
                'Pending',
                'Accepted',
                'Processing'
            ])
            ->oldest()
            ->get();

        return view(
            'admin.kitchen.index',
            compact('orders')
        );
    }

    public function checkNew(): JsonResponse
    {
        $order = Order::where('status', 'Accepted')
            ->where('kitchen_notified', false)
            ->first();

        if ($order) {

            $order->update([
                'kitchen_notified' => true
            ]);

            return response()->json([
                'new' => true,
                'order' => $order->order_number
            ]);
        }

        return response()->json([
            'new' => false
        ]);
    }
}
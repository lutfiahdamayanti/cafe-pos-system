<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with('menu')->get();

        $subtotal = $carts->sum('total');
        $tax = $subtotal * 0.11;
        $service = 3000;
        $grandTotal = $subtotal + $tax + $service;

        return view('customer.checkout', compact(
            'carts',
            'subtotal',
            'tax',
            'service',
            'grandTotal'
        ));
    }

    public function store(Request $request)
    {
    $carts = Cart::with('menu')->get();

    if ($carts->isEmpty()) {
        return back()->with('error', 'Keranjang masih kosong.');
    }

    $subtotal = $carts->sum('total');
    $tax = $subtotal * 0.11;
    $service = 3000;
    $grandTotal = $subtotal + $tax + $service;

    $order = Order::create([

        'order_number' => 'ORD'.date('YmdHis'),

        'customer_name' => $request->customer_name,

        'phone' => $request->phone,

        'table_number' => session('table_number') ?? $request->table_number,

        'visit_type' => $request->visit_type,

        'payment' => $request->payment,

        'note' => $request->note,

        'subtotal' => $subtotal,

        'tax' => $tax,

        'service' => $service,

        'total' => $grandTotal,

        'status' => 'Pending',

    ]);

    foreach($carts as $cart){

        OrderDetail::create([

            'order_id' => $order->id,

            'menu_id' => $cart->menu_id,

            'qty' => $cart->qty,

            'size' => $cart->size,

            'price' => $cart->price,

            'total' => $cart->total,

            'note' => $cart->note,

        ]);

    }

    Cart::truncate();
    session()->forget('table_number');
    return redirect()->route('tracking');
    }
}
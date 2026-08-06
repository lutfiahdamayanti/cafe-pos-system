<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
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

    $today = now()->format('Y-m-d');
    $queueNumber = Order::whereDate('created_at', $today)->count() + 1;
    $queueDelay = ($queueNumber - 1) * 3;
    $estimatedTime = $queueDelay;
    foreach ($carts as $cart) {
        $estimatedTime += $cart->menu->preparation_time * $cart->qty;
    }

    $order = Order::create([
        'order_number' => 'ORD'.date('YmdHis'),
        'queue_number' => $queueNumber,
        'estimated_time' => $estimatedTime,

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

    $customer = Customer::firstOrCreate(
    [
        'phone' => $request->phone
    ],
    [
        'name' => $request->customer_name,
        'email' => $request->email,
        'visit_count' => 1,
        'total_spending' => $grandTotal,
        'last_visit' => now(),
    ]
    );

    if(!$customer->wasRecentlyCreated){
        $customer->update([
            'name' => $request->customer_name,
            'visit_count' => $customer->visit_count + 1,
            'total_spending' => $customer->total_spending + $grandTotal,
            'last_visit' => now(),
        ]);
    }

    foreach($carts as $cart){
        OrderDetail::create([
            'order_id' => $order->id,
            'menu_id' => $cart->menu_id,
            'qty' => $cart->qty,
            'size' => $cart->size,
            'options' => $cart->options,
            'price' => $cart->price,
            'total' => $cart->total,
            'note' => $cart->note,
        ]);
        $menu = $cart->menu;
        $menu->decrement('stock', $cart->qty);
    }

    Cart::truncate();
    session()->forget('table_number');
    return redirect()->route('tracking');
    }
}
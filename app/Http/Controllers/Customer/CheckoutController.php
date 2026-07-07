<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;

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

    public function store()
    {
        return redirect('/')
            ->with('success','Pesanan berhasil dibuat.');
    }
}
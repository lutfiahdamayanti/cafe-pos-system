<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Menu;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $carts = Cart::with('menu')->latest()->get();

        return view('customer.cart', compact('carts'));
    }

    // Menyimpan ke keranjang
    public function store(Request $request)
    {
    $menu = Menu::findOrFail($request->menu_id);

    Cart::create([
        'menu_id' => $menu->id,
        'qty' => $request->qty,
        'size' => $request->size,
        'note' => $request->note,
        'price' => $menu->price + $request->size,
        'total' => ($menu->price + $request->size) * $request->qty,
    ]);

    return redirect()->route('cart.index')
        ->with('success', 'Menu berhasil ditambahkan ke keranjang.');
    }
}
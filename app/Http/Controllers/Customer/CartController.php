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

    $price = $menu->price + $request->size;

    Cart::create([
        'menu_id' => $menu->id,
        'qty' => $request->qty,
        'size' => $request->size == 0 ? 'Regular' : 'Large',
        'note' => $request->note,
        'price' => $price,
        'total' => $price * $request->qty,
    ]);

    return redirect()->route('cart.index')
        ->with('success', 'Menu berhasil ditambahkan ke keranjang.');
    }
}
<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\MenuOptionValue;

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

        $options = [];
        $extraPrice = 0;

        if ($request->has('options')) {

            foreach ($request->options as $valueId) {

                $value = MenuOptionValue::with('option')->find($valueId);

                if ($value) {

                    $options[$value->option->name] = $value->value;

                    $extraPrice += $value->extra_price;

                }
            }
        }

        $price = $menu->price + $extraPrice;

        Cart::create([
            'menu_id' => $menu->id,
            'qty' => $request->qty,
            'options' => $options,
            'note' => $request->note,
            'price' => $price,
            'total' => $price * $request->qty,
        ]);

        return redirect()->route('cart.index')
            ->with('success','Menu berhasil ditambahkan ke keranjang.');
    }

    public function updateQty(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);

        $cart->qty = $request->qty;
        $cart->total = $cart->price * $cart->qty;
        $cart->save();

        return back();
    }
}
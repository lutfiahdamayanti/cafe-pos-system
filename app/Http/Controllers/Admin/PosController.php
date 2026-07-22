<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Customer;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        $menus = Menu::with('options.values')->get();

        $tables = [
            'A01',
            'A02',
            'A03',
            'A04',
            'A05',
            'A06',
            'A07',
            'A08'
        ];

        return view(
            'admin.pos.index',
            compact('menus', 'tables')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'phone' => 'required',
            'table_number' => 'required',
            'payment' => 'required',
            'menus' => 'required|array|min:1',
        ]);

        $subtotal = 0;

        // ==========================
        // Hitung Total
        // ==========================
        foreach ($request->menus as $item) {

            $menu = Menu::with('options.values')->findOrFail($item['id']);

            $price = $menu->price;

            // Ukuran Large
            if (!empty($item['size']) && $item['size'] == 'Large') {
                $price += $menu->large_price;
            }

            // Tambahan harga option
            if (!empty($item['options'])) {

                foreach ($item['options'] as $valueId) {

                    $value = \App\Models\MenuOptionValue::find($valueId);

                    if ($value) {
                        $price += $value->extra_price;
                    }
                }
            }

            $subtotal += ($price * $item['qty']);
        }

        $tax = $subtotal * 0.11;
        $service = 3000;
        $total = $subtotal + $tax + $service;

        // ==========================
        // Simpan Order
        // ==========================
        $order = Order::create([
            'order_number'  => 'ORD' . date('YmdHis'),
            'customer_name' => $request->customer_name,
            'phone'         => $request->phone,
            'table_number'  => $request->table_number,
            'payment'       => $request->payment,
            'subtotal'      => $subtotal,
            'tax'           => $tax,
            'service'       => $service,
            'total'         => $total,
            'status'        => 'Pending'
        ]);

        // ==========================
        // Simpan Detail Order
        // ==========================
        foreach ($request->menus as $item) {

            $menu = Menu::findOrFail($item['id']);

            $price = $menu->price;

            if (!empty($item['size']) && $item['size'] == 'Large') {
                $price += $menu->large_price;
            }

            $selectedOptions = [];
            $extraPrice = 0;

            if (!empty($item['options'])) {

                foreach ($item['options'] as $valueId) {

                    $value = \App\Models\MenuOptionValue::with('option')->find($valueId);

                    if ($value) {

                        $selectedOptions[$value->option->name] = $value->value;

                        $extraPrice += $value->extra_price;
                    }
                }
            }

            $price += $extraPrice;

            OrderDetail::create([
                'order_id' => $order->id,
                'menu_id'  => $menu->id,
                'qty'      => $item['qty'],
                'size'     => $item['size'] ?? null,
                'options'  => $selectedOptions,
                'note'     => $item['note'] ?? null,
                'price'    => $price,
                'total'    => $price * $item['qty']
            ]);

            $menu->decrement('stock', $item['qty']);
        }

        // ==========================
        // Customer
        // ==========================
        $customer = Customer::firstOrCreate(
            [
                'phone' => $request->phone
            ],
            [
                'name' => $request->customer_name,
                'visit_count' => 1,
                'total_spending' => $total,
                'last_visit' => now()
            ]
        );

        if (!$customer->wasRecentlyCreated) {

            $customer->update([
                'name' => $request->customer_name,
                'visit_count' => $customer->visit_count + 1,
                'total_spending' => $customer->total_spending + $total,
                'last_visit' => now()
            ]);
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil dibuat.');
    }
}
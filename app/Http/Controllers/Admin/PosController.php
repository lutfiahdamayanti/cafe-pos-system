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
        $customers = Customer::orderBy('name')->get();

        $tables = [
            'A01','A02','A03','A04',
            'A05','A06','A07','A08'
        ];

        return view('admin.pos.index', compact('menus', 'tables', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'phone' => 'required',
            'visit_type' => 'required|in:Dine In,Pickup,Delivery,Pre-order',
            'table_number' => 'nullable',
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|in:Cash,QRIS,E-Wallet,Virtual Account',
            'payments.*.amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'menus' => 'required|array|min:1',
        ]);

        $subtotal = 0;

        foreach ($request->menus as $item) {
            $menu = Menu::with('options.values')->findOrFail($item['id']);
            $price = $menu->price;

            if (!empty($item['size']) && $item['size'] == 'Large') {
                $price += $menu->large_price;
            }

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

        $discount = $request->discount ?? 0;

        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $afterDiscount = $subtotal - $discount;
        $tax = round($afterDiscount * 0.11);
        $service = 3000;
        $total = round($afterDiscount + $tax + $service);
        $paymentTotal = collect($request->payments)->sum('amount');

        if ($paymentTotal != (int) $total) {
            return back()
                ->withInput()
                ->withErrors([
                    'payments' => 'Total pembayaran harus sama dengan total pesanan.'
                ]);
        }

        $order = Order::create([
            'order_number'  => 'ORD' . date('YmdHis'),
            'customer_name' => $request->customer_name,
            'phone'         => $request->phone,
            'table_number'  => $request->table_number,
            'visit_type'    => $request->visit_type,
            'payment' => collect($request->payments)
                ->map(function ($payment) {
                    return $payment['method'] . ': Rp ' . number_format($payment['amount'], 0, ',', '.');
                })
                ->implode(', '),
            'subtotal'      => $subtotal,
            'discount'      => $discount,
            'tax'           => $tax,
            'service'       => $service,
            'total'         => $total,
            'status'        => 'Pending'
        ]);

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

        $customer = Customer::firstOrCreate(
            ['phone' => $request->phone],
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
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    // ========================================================== EXPORT CSV ==========================================================
    public function exportCsv()
    {
        $orders = Order::all();

        $filename = 'Laporan_Order_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($orders) {

            $file = fopen('php://output', 'w');

            // UTF-8 BOM supaya Excel tidak aneh
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv(
                $file,
                [
                    'No Order',
                    'Pelanggan',
                    'Pembayaran',
                    'Status',
                    'Total',
                    'Tanggal'
                ],
                ';'
            );

            // Data
            foreach ($orders as $order) {

                fputcsv(
                    $file,
                    [
                        $order->order_number,
                        $order->customer_name,
                        $order->payment,
                        $order->status,
                        $order->total,
                        $order->created_at->format('d/m/Y')
                    ],
                    ';'
                );
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
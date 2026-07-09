<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $period = request('period', 'daily');

        $query = Order::query();

        switch ($period) {

            case 'weekly':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;

            case 'monthly':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;

            case 'yearly':
                $query->whereYear('created_at', Carbon::now()->year);
                break;

            default:
                $query->whereDate('created_at', Carbon::today());
        }

        $orders = $query->get();

        // ================= ANALISIS =================

        $completed = $orders->where('status', 'Completed')->count();

        $cancelled = $orders->where('status', 'Cancelled')->count();

        $refund = 0;

        $grossRevenue = $orders->sum('total');

        $tax = $orders->sum('tax');

        $service = $orders->sum('service');

        $netRevenue = $grossRevenue - $tax - $service;

        // ================= DATA GRAFIK =================

        $labels = [];
        $data = [];

        if ($period == 'daily') {

            for ($i = 0; $i < 24; $i++) {

                $labels[] = sprintf('%02d:00', $i);

                $data[] = Order::whereDate('created_at', today())
                    ->where(DB::raw('HOUR(created_at)'), $i)
                    ->sum('total');
            }

        } elseif ($period == 'weekly') {

            for ($i = 0; $i < 7; $i++) {

                $date = now()->startOfWeek()->copy()->addDays($i);

                $labels[] = $date->format('D');

                $data[] = Order::whereDate('created_at', $date)
                    ->sum('total');
            }

        } elseif ($period == 'monthly') {

            $days = now()->daysInMonth;

            for ($i = 1; $i <= $days; $i++) {

                $labels[] = $i;

                $data[] = Order::whereDay('created_at', $i)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total');
            }

        } else {

            for ($i = 1; $i <= 12; $i++) {

                $labels[] = date('M', mktime(0, 0, 0, $i, 1));

                $data[] = Order::whereMonth('created_at', $i)
                    ->whereYear('created_at', now()->year)
                    ->sum('total');
            }
        }

        // ================= METODE PEMBAYARAN =================

        $paymentLabels = [
            'Cash',
            'QRIS',
            'E-Wallet',
            'Virtual Account'
        ];

        $paymentData = [

            Order::where('payment', 'Cash')->count(),

            Order::where('payment', 'QRIS')->count(),

            Order::where('payment', 'E-Wallet')->count(),

            Order::where('payment', 'Virtual Account')->count(),

        ];

        // ================= TOP 10 BEST SELLER =================

        $bestSeller = OrderDetail::select(
                'menu_id',
                DB::raw('SUM(qty) as total_qty')
            )
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // ================= TOP 10 WORST SELLER =================

        $worstSeller = OrderDetail::select(
                'menu_id',
                DB::raw('SUM(qty) as total_qty')
            )
            ->with('menu')
            ->groupBy('menu_id')
            ->orderBy('total_qty')
            ->take(10)
            ->get();

        // ================= PEAK HOURS =================

        $peakHours = Order::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderByDesc('total')
            ->get();

        // ================= PEAK DAYS =================

        $peakDays = Order::select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('DAYNAME(created_at)'))
            ->orderByDesc('total')
            ->get();

        // ================= RETURNING CUSTOMER =================

        $returning = Order::select('phone')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->groupBy('phone')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        $newCustomer = Order::select('phone')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->groupBy('phone')
            ->havingRaw('COUNT(*) = 1')
            ->get()
            ->count();

        return view('admin.reports.index', compact(

            'orders',
            'period',

            'completed',
            'cancelled',
            'refund',

            'grossRevenue',
            'netRevenue',
            'tax',
            'service',

            'labels',
            'data',

            'paymentLabels',
            'paymentData',

            'bestSeller',
            'worstSeller',

            'peakHours',
            'peakDays',

            'returning',
            'newCustomer'
        ));
    }
}
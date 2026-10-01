<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use App\Models\Customer;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerReportController extends Controller
{
    /**
     * Helper untuk filter tanggal periode laporan
     */
    private function resolveDateRange(Request $request): array
    {
        $period = $request->get('period', 'this_month');
        $today = Carbon::today();

        switch ($period) {
            case 'today':
                $start = $today->copy()->startOfDay();
                $end = $today->copy()->endOfDay();
                $label = 'Hari Ini (' . $today->format('d M Y') . ')';
                break;
            case 'yesterday':
                $start = $today->copy()->subDay()->startOfDay();
                $end = $today->copy()->subDay()->endOfDay();
                $label = 'Kemarin (' . $today->copy()->subDay()->format('d M Y') . ')';
                break;
            case 'this_week':
                $start = $today->copy()->startOfWeek();
                $end = $today->copy()->endOfWeek();
                $label = 'Minggu Ini (' . $start->format('d M') . ' - ' . $end->format('d M Y') . ')';
                break;
            case 'last_month':
                $start = $today->copy()->subMonth()->startOfMonth();
                $end = $today->copy()->subMonth()->endOfMonth();
                $label = 'Bulan Lalu (' . $start->format('F Y') . ')';
                break;
            case 'this_year':
                $start = $today->copy()->startOfYear();
                $end = $today->copy()->endOfYear();
                $label = 'Tahun Ini (' . $today->year . ')';
                break;
            case 'custom':
                $start = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : $today->copy()->startOfMonth();
                $end = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : $today->copy()->endOfDay();
                $label = $start->format('d M Y') . ' s/d ' . $end->format('d M Y');
                break;
            case 'this_month':
            default:
                $period = 'this_month';
                $start = $today->copy()->startOfMonth();
                $end = $today->copy()->endOfMonth();
                $label = 'Bulan Ini (' . $today->format('F Y') . ')';
                break;
        }

        return [$start, $end, $period, $label];
    }

    /**
     * =========================================================================
     * 1. OMZET & TREN PENJUALAN
     * URL: /admin/owner-reports/omzet-tren
     * =========================================================================
     */
    public function omzetTren(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        $ordersQuery = Order::whereBetween('created_at', [$start, $end]);
        $completedOrdersQuery = (clone $ordersQuery)->where('status', 'Completed');

        // Ringkasan Finansial
        $totalTransactions = $completedOrdersQuery->count();
        $grossSales = $completedOrdersQuery->sum('total');
        $totalTax = $completedOrdersQuery->sum('tax');
        $totalService = $completedOrdersQuery->sum('service');
        $netSales = $grossSales - $totalTax - $totalService;
        $avgOrderValue = $totalTransactions > 0 ? round($grossSales / $totalTransactions) : 0;

        $cancelledCount = (clone $ordersQuery)->where('status', 'Cancelled')->count();

        // Grafik Tren Penjualan Harian / Bulanan
        $chartLabels = [];
        $chartSalesData = [];
        $chartOrderCountData = [];

        if (in_array($period, ['today', 'yesterday'])) {
            // Per Jam (00 - 23)
            for ($h = 7; $h <= 23; $h++) {
                $chartLabels[] = sprintf('%02d:00', $h);
                $sales = (clone $completedOrdersQuery)
                    ->whereRaw('HOUR(created_at) = ?', [$h])
                    ->sum('total');
                $count = (clone $completedOrdersQuery)
                    ->whereRaw('HOUR(created_at) = ?', [$h])
                    ->count();
                $chartSalesData[] = (int) $sales;
                $chartOrderCountData[] = $count;
            }
        } elseif ($period === 'this_year') {
            // Per Bulan (Jan - Des)
            for ($m = 1; $m <= 12; $m++) {
                $chartLabels[] = Carbon::create(null, $m, 1)->format('M');
                $sales = Order::where('status', 'Completed')
                    ->whereYear('created_at', $start->year)
                    ->whereMonth('created_at', $m)
                    ->sum('total');
                $count = Order::where('status', 'Completed')
                    ->whereYear('created_at', $start->year)
                    ->whereMonth('created_at', $m)
                    ->count();
                $chartSalesData[] = (int) $sales;
                $chartOrderCountData[] = $count;
            }
        } else {
            // Per Hari dalam rentang tanggal
            $curr = $start->copy();
            $maxDays = min(31, $start->diffInDays($end) + 1);
            for ($d = 0; $d < $maxDays; $d++) {
                $dateStr = $curr->format('Y-m-d');
                $chartLabels[] = $curr->format('d M');
                $sales = Order::where('status', 'Completed')
                    ->whereDate('created_at', $dateStr)
                    ->sum('total');
                $count = Order::where('status', 'Completed')
                    ->whereDate('created_at', $dateStr)
                    ->count();
                $chartSalesData[] = (int) $sales;
                $chartOrderCountData[] = $count;
                $curr->addDay();
            }
        }

        // Breakdown Metode Pembayaran
        $paymentBreakdown = (clone $completedOrdersQuery)
            ->select('payment', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total) as total_amount'))
            ->groupBy('payment')
            ->get();

        // Breakdown Tipe Kunjungan
        $visitBreakdown = (clone $completedOrdersQuery)
            ->select('visit_type', DB::raw('COUNT(*) as total_orders'), DB::raw('SUM(total) as total_amount'))
            ->groupBy('visit_type')
            ->get();

        // Transaksi Terkini
        $recentOrders = (clone $completedOrdersQuery)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.owner_reports.omzet_tren', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'totalTransactions',
            'grossSales',
            'netSales',
            'totalTax',
            'totalService',
            'avgOrderValue',
            'cancelledCount',
            'chartLabels',
            'chartSalesData',
            'chartOrderCountData',
            'paymentBreakdown',
            'visitBreakdown',
            'recentOrders'
        ));
    }

    /**
     * =========================================================================
     * 2. FOOD COST & COGS (Harga Pokok Penjualan)
     * URL: /admin/owner-reports/food-cost-cogs
     * =========================================================================
     */
    public function foodCostCogs(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        // Ambil rincian menu yang terjual pada rentang tanggal
        $orderItems = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->leftJoin('categories', 'menus.category_id', '=', 'categories.id')
            ->where('orders.status', 'Completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'menus.id as menu_id',
                'menus.name as menu_name',
                'menus.image',
                'categories.name as category_name',
                'menus.price',
                'menus.food_cost',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.total) as total_revenue')
            )
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'categories.name', 'menus.price', 'menus.food_cost')
            ->get();

        // Hitung Total Omzet & Total COGS
        $totalSalesRevenue = 0;
        $totalCOGS = 0;

        foreach ($orderItems as $item) {
            $effectiveFoodCost = $item->food_cost > 0 ? (float)$item->food_cost : round($item->price * 0.32);
            $item->effective_food_cost = $effectiveFoodCost;
            $item->item_cogs = $effectiveFoodCost * $item->total_qty;
            $item->cost_ratio = $item->price > 0 ? round(($effectiveFoodCost / $item->price) * 100, 1) : 0;

            $totalSalesRevenue += $item->total_revenue;
            $totalCOGS += $item->item_cogs;
        }

        $overallFoodCostPercentage = $totalSalesRevenue > 0 ? round(($totalCOGS / $totalSalesRevenue) * 100, 1) : 0;

        // Status Efisiensi Food Cost
        $costHealth = match(true) {
            $overallFoodCostPercentage <= 30 => ['status' => 'Sangat Sehat', 'class' => 'text-success', 'badge' => 'bg-success', 'desc' => 'Efisiensi bahan baku sangat baik (< 30%).'],
            $overallFoodCostPercentage <= 35 => ['status' => 'Optimal / Standar', 'class' => 'text-primary', 'badge' => 'bg-primary', 'desc' => 'Sesuai standar industri kafe & resto (30% - 35%).'],
            $overallFoodCostPercentage <= 40 => ['status' => 'Perlu Perhatian', 'class' => 'text-warning', 'badge' => 'bg-warning text-dark', 'desc' => 'Food cost mendekati batas atas (35% - 40%).'],
            default => ['status' => 'Waspada / Boros', 'class' => 'text-danger', 'badge' => 'bg-danger', 'desc' => 'Food cost di atas 40%, perlu evaluasi porsi atau negosiasi supplier.'],
        };

        // Semua menu untuk form update HPP
        $allMenus = Menu::with('category')->orderBy('name')->get();

        return view('admin.owner_reports.food_cost_cogs', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'orderItems',
            'totalSalesRevenue',
            'totalCOGS',
            'overallFoodCostPercentage',
            'costHealth',
            'allMenus'
        ));
    }

    /**
     * Update cepat Food Cost (HPP) per menu
     */
    public function updateMenuFoodCost(Request $request, Menu $menu)
    {
        $request->validate([
            'food_cost' => 'required|numeric|min:0'
        ]);

        $menu->update(['food_cost' => $request->food_cost]);

        return back()->with('success', "Food Cost (HPP) menu '{$menu->name}' berhasil diperbarui menjadi Rp " . number_format($request->food_cost, 0, ',', '.'));
    }

    /**
     * =========================================================================
     * 3. LABA KOTOR & MARGIN (Gross Profit & Margin)
     * URL: /admin/owner-reports/laba-margin
     * =========================================================================
     */
    public function labaMargin(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        $completedOrders = Order::where('status', 'Completed')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $grossSales = $completedOrders->sum('total');
        $tax = $completedOrders->sum('tax');
        $service = $completedOrders->sum('service');
        $netRevenue = $grossSales - $tax - $service;

        // Ambil order details untuk hitung COGS
        $orderDetails = OrderDetail::with('menu')
            ->whereIn('order_id', $completedOrders->pluck('id'))
            ->get();

        $totalCogs = 0;
        foreach ($orderDetails as $d) {
            $unitCost = ($d->menu && $d->menu->food_cost > 0) ? $d->menu->food_cost : ($d->price * 0.32);
            $totalCogs += ($unitCost * $d->qty);
        }

        $grossProfit = $netRevenue - $totalCogs;
        $grossMarginPct = $netRevenue > 0 ? round(($grossProfit / $netRevenue) * 100, 1) : 0;

        // Breakdown Profit & Margin berdasarkan Kategori
        $categoryBreakdown = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->leftJoin('categories', 'menus.category_id', '=', 'categories.id')
            ->where('orders.status', 'Completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                DB::raw('COALESCE(categories.name, "Lain-lain") as category_name'),
                DB::raw('SUM(order_details.total) as revenue'),
                DB::raw('SUM(order_details.qty * IF(menus.food_cost > 0, menus.food_cost, menus.price * 0.32)) as cogs')
            )
            ->groupBy('category_name')
            ->get()
            ->map(function ($cat) {
                $cat->profit = $cat->revenue - $cat->cogs;
                $cat->margin_pct = $cat->revenue > 0 ? round(($cat->profit / $cat->revenue) * 100, 1) : 0;
                return $cat;
            });

        // Grafik Tren Laba Kotor per Hari / Bulan
        $chartLabels = [];
        $chartRevenue = [];
        $chartCogs = [];
        $chartProfit = [];

        $curr = $start->copy();
        $daysCount = min(31, $start->diffInDays($end) + 1);

        for ($i = 0; $i < $daysCount; $i++) {
            $dateStr = $curr->format('Y-m-d');
            $chartLabels[] = $curr->format('d M');

            $dayOrders = Order::where('status', 'Completed')
                ->whereDate('created_at', $dateStr)
                ->get();

            $dayRev = $dayOrders->sum('total') - $dayOrders->sum('tax') - $dayOrders->sum('service');

            $dayDetails = OrderDetail::with('menu')
                ->whereIn('order_id', $dayOrders->pluck('id'))
                ->get();

            $dayCogs = 0;
            foreach ($dayDetails as $d) {
                $cost = ($d->menu && $d->menu->food_cost > 0) ? $d->menu->food_cost : ($d->price * 0.32);
                $dayCogs += ($cost * $d->qty);
            }

            $chartRevenue[] = (int) $dayRev;
            $chartCogs[] = (int) $dayCogs;
            $chartProfit[] = (int) ($dayRev - $dayCogs);

            $curr->addDay();
        }

        return view('admin.owner_reports.laba_margin', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'grossSales',
            'netRevenue',
            'totalCogs',
            'grossProfit',
            'grossMarginPct',
            'categoryBreakdown',
            'chartLabels',
            'chartRevenue',
            'chartCogs',
            'chartProfit'
        ));
    }

    /**
     * =========================================================================
     * 4. PRODUK PALING MENGUNTUNGKAN (Menu Engineering Matrix)
     * URL: /admin/owner-reports/produk-menguntungkan
     * =========================================================================
     */
    public function produkMenguntungkan(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        $orderItems = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->leftJoin('categories', 'menus.category_id', '=', 'categories.id')
            ->where('orders.status', 'Completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'menus.id as menu_id',
                'menus.name as menu_name',
                'menus.image',
                'categories.name as category_name',
                'menus.price',
                'menus.food_cost',
                DB::raw('SUM(order_details.qty) as total_qty'),
                DB::raw('SUM(order_details.total) as total_revenue')
            )
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'categories.name', 'menus.price', 'menus.food_cost')
            ->get();

        if ($orderItems->isEmpty()) {
            // Fallback jika belum ada pesanan pada rentang waktu: tampilkan katalog menu
            $orderItems = Menu::with('category')->get()->map(function($m){
                return (object)[
                    'menu_id' => $m->id,
                    'menu_name' => $m->name,
                    'image' => $m->image,
                    'category_name' => $m->category->name ?? 'Menu',
                    'price' => $m->price,
                    'food_cost' => $m->food_cost,
                    'total_qty' => 0,
                    'total_revenue' => 0,
                ];
            });
        }

        // Hitung Laba per Porsi, Total Laba, dan Margin
        $avgQty = $orderItems->avg('total_qty') ?? 1;
        $totalOverallProfit = 0;

        foreach ($orderItems as $item) {
            $effectiveCost = $item->food_cost > 0 ? (float)$item->food_cost : round($item->price * 0.32);
            $item->effective_cost = $effectiveCost;
            $item->profit_per_unit = $item->price - $effectiveCost;
            $item->total_profit = $item->profit_per_unit * $item->total_qty;
            $item->margin_pct = $item->price > 0 ? round(($item->profit_per_unit / $item->price) * 100, 1) : 0;

            $totalOverallProfit += $item->total_profit;
        }

        $avgMargin = $orderItems->avg('margin_pct') ?? 65;

        // Klasifikasi Menu Engineering (Stars, Puzzles, Plowhorses, Dogs)
        foreach ($orderItems as $item) {
            $highPopularity = $item->total_qty >= $avgQty;
            $highProfitability = $item->margin_pct >= $avgMargin;

            if ($highPopularity && $highProfitability) {
                $item->matrix_class = 'Stars (Bintang)';
                $item->matrix_badge = 'bg-warning text-dark';
                $item->matrix_icon = 'bi-star-fill';
                $item->action_hint = 'Produk andalan! Pertahankan kualitas & resep terbaik.';
            } elseif (!$highPopularity && $highProfitability) {
                $item->matrix_class = 'Puzzles (Teka-Teki)';
                $item->matrix_badge = 'bg-info text-dark';
                $item->matrix_icon = 'bi-puzzle-fill';
                $item->action_hint = 'Margin tinggi tapi penjualan rendah. Dorong promosi & rekomendasikan di kasir.';
            } elseif ($highPopularity && !$highProfitability) {
                $item->matrix_class = 'Plowhorses (Pekerja Keras)';
                $item->matrix_badge = 'bg-primary text-white';
                $item->matrix_icon = 'bi-fire';
                $item->action_hint = 'Sangat laku tapi margin tipis. Naikkan harga sedikit atau efisiensikan bahan.';
            } else {
                $item->matrix_class = 'Dogs (Kurang Profit)';
                $item->matrix_badge = 'bg-secondary text-white';
                $item->matrix_icon = 'bi-exclamation-triangle';
                $item->action_hint = 'Penjualan & margin rendah. Pertimbangkan ganti menu atau evaluasi resep.';
            }
        }

        // Urutkan dari produk dengan kontribusi laba terbesar
        $rankedProducts = $orderItems->sortByDesc('total_profit')->values();

        return view('admin.owner_reports.produk_menguntungkan', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'rankedProducts',
            'totalOverallProfit',
            'avgMargin',
            'avgQty'
        ));
    }

    /**
     * =========================================================================
     * 5. PERTUMBUHAN PELANGGAN (Customer Growth & Retention)
     * URL: /admin/owner-reports/pertumbuhan-pelanggan
     * =========================================================================
     */
    public function pertumbuhanPelanggan(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        $totalCustomers = Customer::count();

        // Pelanggan baru terdaftar pada periode ini
        $newCustomers = Customer::whereBetween('created_at', [$start, $end])->count();

        // Pelanggan yang bertransaksi pada periode ini
        $activeCustomerPhones = Order::where('status', 'Completed')
            ->whereBetween('created_at', [$start, $end])
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->distinct()
            ->pluck('phone');

        $transactingCustomersCount = $activeCustomerPhones->count();

        // Pelanggan repeat (bertransaksi > 1 kali sepanjang waktu)
        $repeatCustomersCount = Customer::whereIn('phone', $activeCustomerPhones)
            ->where('visit_count', '>', 1)
            ->count();

        $retentionRate = $transactingCustomersCount > 0 ? round(($repeatCustomersCount / $transactingCustomersCount) * 100, 1) : 0;

        // Distribusi Membership Tier
        $tierBreakdown = [
            'Bronze' => Customer::where('tier', 'Bronze')->count(),
            'Silver' => Customer::where('tier', 'Silver')->count(),
            'Gold' => Customer::where('tier', 'Gold')->count(),
            'Platinum' => Customer::where('tier', 'Platinum')->count(),
        ];

        // Grafik Pertumbuhan Pelanggan Baru Bulanan (Tahun Berjalan)
        $growthLabels = [];
        $growthData = [];

        for ($m = 1; $m <= 12; $m++) {
            $growthLabels[] = Carbon::create(null, $m, 1)->format('M');
            $growthData[] = Customer::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $m)
                ->count();
        }

        // Top Spenders
        $topSpenders = Customer::orderByDesc('total_spending')->limit(10)->get();

        return view('admin.owner_reports.pertumbuhan_pelanggan', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'totalCustomers',
            'newCustomers',
            'transactingCustomersCount',
            'repeatCustomersCount',
            'retentionRate',
            'tierBreakdown',
            'growthLabels',
            'growthData',
            'topSpenders'
        ));
    }
}

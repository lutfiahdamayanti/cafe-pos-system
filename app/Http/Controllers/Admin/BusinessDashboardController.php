<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\EmployeeKpi;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Outlet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessDashboardController extends Controller
{
    /**
     * Helper resolve date range
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
     * 1. ANALITIK PER CABANG
     * URL: /admin/business-dashboard/cabang
     * =========================================================================
     */
    public function analitikCabang(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        $outlets = Outlet::withCount('orders', 'users')->get();

        $branchStats = [];
        $totalSystemGross = 0;
        $totalSystemOrders = 0;

        foreach ($outlets as $outlet) {
            $orders = Order::where('outlet_id', $outlet->id)
                ->where('status', 'Completed')
                ->whereBetween('created_at', [$start, $end])
                ->get();

            $grossSales = $orders->sum('total');
            $tax = $orders->sum('tax');
            $service = $orders->sum('service');
            $netRevenue = $grossSales - $tax - $service;
            $orderCount = $orders->count();
            $aov = $orderCount > 0 ? round($grossSales / $orderCount) : 0;

            // Jam Sibuk / Peak Hour di cabang ini
            $peakHourData = Order::where('outlet_id', $outlet->id)
                ->where('status', 'Completed')
                ->whereBetween('created_at', [$start, $end])
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
                ->groupBy('hour')
                ->orderByDesc('count')
                ->first();

            $peakHourStr = $peakHourData ? sprintf('%02d:00 - %02d:00 (%d order)', $peakHourData->hour, $peakHourData->hour + 1, $peakHourData->count) : '-';

            // Menu Paling Laris
            $topMenu = DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('menus', 'order_details.menu_id', '=', 'menus.id')
                ->where('orders.outlet_id', $outlet->id)
                ->where('orders.status', 'Completed')
                ->whereBetween('orders.created_at', [$start, $end])
                ->select('menus.name', DB::raw('SUM(order_details.qty) as total_qty'))
                ->groupBy('menus.name')
                ->orderByDesc('total_qty')
                ->first();

            $branchStats[] = (object)[
                'outlet' => $outlet,
                'gross_sales' => $grossSales,
                'net_revenue' => $netRevenue,
                'order_count' => $orderCount,
                'aov' => $aov,
                'peak_hour' => $peakHourStr,
                'top_menu' => $topMenu ? "{$topMenu->name} ({$topMenu->total_qty}x)" : '-',
            ];

            $totalSystemGross += $grossSales;
            $totalSystemOrders += $orderCount;
        }

        // Urutkan berdasarkan omzet tertinggi
        usort($branchStats, fn($a, $b) => $b->gross_sales <=> $a->gross_sales);

        // Chart Data
        $chartLabels = array_map(fn($b) => $b->outlet->name, $branchStats);
        $chartGross = array_map(fn($b) => (int)$b->gross_sales, $branchStats);
        $chartOrders = array_map(fn($b) => (int)$b->order_count, $branchStats);

        $systemAov = $totalSystemOrders > 0 ? round($totalSystemGross / $totalSystemOrders) : 0;

        return view('admin.business_dashboard.cabang', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'branchStats',
            'totalSystemGross',
            'totalSystemOrders',
            'systemAov',
            'chartLabels',
            'chartGross',
            'chartOrders'
        ));
    }

    /**
     * =========================================================================
     * 2. ANALITIK PRODUK
     * URL: /admin/business-dashboard/produk
     * =========================================================================
     */
    public function analitikProduk(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        $query = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->leftJoin('categories', 'menus.category_id', '=', 'categories.id')
            ->where('orders.status', 'Completed')
            ->whereBetween('orders.created_at', [$start, $end]);

        if ($request->filled('category_id')) {
            $query->where('menus.category_id', $request->category_id);
        }

        $items = $query->select(
            'menus.id as menu_id',
            'menus.name as menu_name',
            'menus.image',
            'menus.price',
            'menus.food_cost',
            DB::raw("COALESCE(categories.name, 'Lain-lain') as category_name"),
            DB::raw('SUM(order_details.qty) as total_qty'),
            DB::raw('SUM(order_details.total) as total_revenue')
        )
        ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price', 'menus.food_cost', 'categories.name')
        ->orderByDesc('total_qty')
        ->get();

        // Fallback jika belum ada order
        if ($items->isEmpty()) {
            $items = Menu::with('category')->get()->map(function($m){
                return (object)[
                    'menu_id' => $m->id,
                    'menu_name' => $m->name,
                    'image' => $m->image,
                    'price' => $m->price,
                    'food_cost' => $m->food_cost,
                    'category_name' => $m->category->name ?? 'Menu',
                    'total_qty' => 0,
                    'total_revenue' => 0,
                ];
            });
        }

        $totalUnitsSold = $items->sum('total_qty');
        $totalProductRevenue = $items->sum('total_revenue');

        // Kalkulasi Profit & Margin per produk
        foreach ($items as $item) {
            $unitCost = $item->food_cost > 0 ? (float)$item->food_cost : round($item->price * 0.32);
            $item->unit_cost = $unitCost;
            $item->total_profit = ($item->price - $unitCost) * $item->total_qty;
            $item->margin_pct = $item->price > 0 ? round((($item->price - $unitCost) / $item->price) * 100, 1) : 0;
            $item->volume_share = $totalUnitsSold > 0 ? round(($item->total_qty / $totalUnitsSold) * 100, 1) : 0;
        }

        // Breakdown Kategori Analitik
        $categoryBreakdown = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->leftJoin('categories', 'menus.category_id', '=', 'categories.id')
            ->where('orders.status', 'Completed')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                DB::raw('COALESCE(categories.name, "Uncategorized") as cat_name'),
                DB::raw('SUM(order_details.qty) as cat_qty'),
                DB::raw('SUM(order_details.total) as cat_revenue')
            )
            ->groupBy('cat_name')
            ->orderByDesc('cat_qty')
            ->get();

        $catLabels = $categoryBreakdown->pluck('cat_name');
        $catQty = $categoryBreakdown->pluck('cat_qty');

        $topProduct = $items->first();
        $categories = Category::all();

        return view('admin.business_dashboard.produk', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'items',
            'totalUnitsSold',
            'totalProductRevenue',
            'topProduct',
            'categoryBreakdown',
            'catLabels',
            'catQty',
            'categories'
        ));
    }

    /**
     * =========================================================================
     * 3. ANALITIK PELANGGAN
     * URL: /admin/business-dashboard/pelanggan
     * =========================================================================
     */
    public function analitikPelanggan(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        $totalCustomers = Customer::count();
        $newCustomers = Customer::whereBetween('created_at', [$start, $end])->count();

        // Customer Lifetime Value (CLV / Rata-rata spending per pelanggan)
        $avgSpending = Customer::avg('total_spending') ?? 0;
        $totalCustomerSpending = Customer::sum('total_spending');

        // Repeat Order Rate
        $customersWithOrders = Customer::where('visit_count', '>', 0)->count();
        $repeatCustomers = Customer::where('visit_count', '>', 1)->count();
        $repeatRate = $customersWithOrders > 0 ? round(($repeatCustomers / $customersWithOrders) * 100, 1) : 0;

        // Distribusi Membership Tier
        $tierCounts = [
            'Bronze' => Customer::where('tier', 'Bronze')->count(),
            'Silver' => Customer::where('tier', 'Silver')->count(),
            'Gold' => Customer::where('tier', 'Gold')->count(),
            'Platinum' => Customer::where('tier', 'Platinum')->count(),
        ];

        // Segmentasi Pelanggan (RFM Simpel: New, Loyal, At-Risk, Inactive)
        $loyalCustomersCount = Customer::where('visit_count', '>=', 5)->count();
        $atRiskCount = Customer::where('visit_count', '>=', 2)->where('last_visit', '<', now()->subDays(45))->count();

        // Top Spenders VIP
        $topSpenders = Customer::orderByDesc('total_spending')->limit(10)->get();

        return view('admin.business_dashboard.pelanggan', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'totalCustomers',
            'newCustomers',
            'avgSpending',
            'totalCustomerSpending',
            'repeatRate',
            'tierCounts',
            'loyalCustomersCount',
            'atRiskCount',
            'topSpenders'
        ));
    }

    /**
     * =========================================================================
     * 4. ANALITIK KARYAWAN
     * URL: /admin/business-dashboard/karyawan
     * =========================================================================
     */
    public function analitikKaryawan(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $employees = Employee::with(['outlet', 'kpis' => function($q) use ($month, $year) {
            $q->where('period_month', $month)->where('period_year', $year);
        }])->where('status', 'active')->get();

        $totalEmployees = $employees->count();

        // Kehadiran dan Kedisiplinan Bulan ini
        $attendances = Attendance::whereMonth('date', $month)->whereYear('date', $year)->get();
        $totalAttendances = $attendances->count();
        $ontimeAttendances = $attendances->where('status', 'Hadir')->where('late_minutes', 0)->count();
        $disciplinePct = $totalAttendances > 0 ? round(($ontimeAttendances / $totalAttendances) * 100, 1) : 0;

        // KPI Averages
        $kpiRecords = EmployeeKpi::where('period_month', $month)->where('period_year', $year)->get();
        $avgKpiScore = $kpiRecords->avg('final_score') ?? 0;
        $countGradeA = $kpiRecords->where('grade', 'A')->count();
        $countGradeB = $kpiRecords->where('grade', 'B')->count();
        $countGradeCD = $kpiRecords->whereIn('grade', ['C', 'D'])->count();

        // Top Employee Performer
        $topPerformerKpi = $kpiRecords->sortByDesc('final_score')->first();

        // Per-Employee Analytics Table Data
        $employeeStats = [];
        foreach ($employees as $emp) {
            $empAtt = $attendances->where('employee_id', $emp->id);
            $hadirCount = $empAtt->where('status', 'Hadir')->count();
            $lateCount = $empAtt->where('late_minutes', '>', 0)->count();
            $kpi = $emp->kpis->first();

            $employeeStats[] = (object)[
                'employee' => $emp,
                'hadir_count' => $hadirCount,
                'late_count' => $lateCount,
                'kpi_score' => $kpi ? $kpi->final_score : null,
                'grade' => $kpi ? $kpi->grade : '-',
            ];
        }

        // Urutkan dari KPI score tertinggi
        usort($employeeStats, function($a, $b) {
            return ($b->kpi_score ?? 0) <=> ($a->kpi_score ?? 0);
        });

        return view('admin.business_dashboard.karyawan', compact(
            'month',
            'year',
            'totalEmployees',
            'disciplinePct',
            'avgKpiScore',
            'countGradeA',
            'countGradeB',
            'countGradeCD',
            'topPerformerKpi',
            'employeeStats'
        ));
    }
}

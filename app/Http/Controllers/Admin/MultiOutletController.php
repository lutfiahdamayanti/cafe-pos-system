<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentralWarehouseStock;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Outlet;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MultiOutletController extends Controller
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
     * 1. KELOLA BANYAK CABANG
     * URL: /admin/multi-outlet/cabang
     * =========================================================================
     */
    public function cabang(Request $request)
    {
        $query = Outlet::withCount('orders', 'users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('manager_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $outlets = $query->orderBy('id', 'asc')->get();

        $totalOutlets = Outlet::count();
        $activeOutlets = Outlet::where('status', 'active')->count();
        $warehouseCount = Outlet::where('is_central_warehouse', true)->count();
        $totalCities = Outlet::distinct('city')->whereNotNull('city')->count('city');

        return view('admin.multi_outlet.cabang', compact(
            'outlets',
            'totalOutlets',
            'activeOutlets',
            'warehouseCount',
            'totalCities'
        ));
    }

    public function storeCabang(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:outlets,code',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'manager_name' => 'nullable|string|max:100',
            'operating_hours' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        Outlet::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager_name' => $request->manager_name,
            'operating_hours' => $request->operating_hours ?? '08:00 - 22:00',
            'status' => $request->status,
            'is_central_warehouse' => $request->has('is_central_warehouse'),
        ]);

        return back()->with('success', "Cabang '{$request->name}' berhasil ditambahkan!");
    }

    public function updateCabang(Request $request, Outlet $outlet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => "required|string|max:50|unique:outlets,code,{$outlet->id}",
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'manager_name' => 'nullable|string|max:100',
            'operating_hours' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $outlet->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'city' => $request->city,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'manager_name' => $request->manager_name,
            'operating_hours' => $request->operating_hours ?? '08:00 - 22:00',
            'status' => $request->status,
            'is_central_warehouse' => $request->has('is_central_warehouse'),
        ]);

        return back()->with('success', "Data cabang '{$outlet->name}' berhasil diperbarui!");
    }

    public function toggleStatusCabang(Outlet $outlet)
    {
        $newStatus = $outlet->status === 'active' ? 'inactive' : 'active';
        $outlet->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Cabang '{$outlet->name}' berhasil {$statusText}!");
    }

    public function destroyCabang(Outlet $outlet)
    {
        if ($outlet->orders()->count() > 0) {
            return back()->with('error', "Cabang '{$outlet->name}' tidak dapat dihapus karena sudah memiliki data transaksi.");
        }

        $outlet->delete();
        return back()->with('success', "Cabang berhasil dihapus!");
    }

    /**
     * =========================================================================
     * 2. PERFORMA & LAPORAN PER CABANG
     * URL: /admin/multi-outlet/performa
     * =========================================================================
     */
    public function performa(Request $request)
    {
        [$start, $end, $period, $periodLabel] = $this->resolveDateRange($request);

        $outlets = Outlet::all();

        $performanceData = [];
        $totalSystemGross = 0;
        $totalSystemNet = 0;
        $totalSystemOrders = 0;

        foreach ($outlets as $outlet) {
            $orders = Order::where('outlet_id', $outlet->id)
                ->where('status', 'Completed')
                ->whereBetween('created_at', [$start, $end])
                ->get();

            $orderCount = $orders->count();
            $grossSales = $orders->sum('total');
            $tax = $orders->sum('tax');
            $service = $orders->sum('service');
            $netRevenue = $grossSales - $tax - $service;
            $aov = $orderCount > 0 ? round($grossSales / $orderCount) : 0;

            // Tipe Kunjungan
            $dineInCount = $orders->where('visit_type', 'Dine In')->count();
            $takeawayCount = $orders->whereIn('visit_type', ['Pickup', 'Delivery', 'Pre-order'])->count();

            // Menu Terlaris di cabang ini
            $topItem = DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->join('menus', 'order_details.menu_id', '=', 'menus.id')
                ->where('orders.outlet_id', $outlet->id)
                ->where('orders.status', 'Completed')
                ->whereBetween('orders.created_at', [$start, $end])
                ->select('menus.name', DB::raw('SUM(order_details.qty) as total_qty'))
                ->groupBy('menus.name')
                ->orderByDesc('total_qty')
                ->first();

            $performanceData[] = (object)[
                'outlet' => $outlet,
                'order_count' => $orderCount,
                'gross_sales' => $grossSales,
                'net_revenue' => $netRevenue,
                'aov' => $aov,
                'dine_in_count' => $dineInCount,
                'takeaway_count' => $takeawayCount,
                'top_item' => $topItem ? "{$topItem->name} ({$topItem->total_qty}x)" : '-',
            ];

            $totalSystemGross += $grossSales;
            $totalSystemNet += $netRevenue;
            $totalSystemOrders += $orderCount;
        }

        // Sort performance by Gross Sales descending
        usort($performanceData, fn($a, $b) => $b->gross_sales <=> $a->gross_sales);

        // Chart Data
        $chartLabels = array_map(fn($p) => $p->outlet->name, $performanceData);
        $chartSales = array_map(fn($p) => (int)$p->gross_sales, $performanceData);
        $chartOrders = array_map(fn($p) => (int)$p->order_count, $performanceData);

        return view('admin.multi_outlet.performa', compact(
            'period',
            'periodLabel',
            'start',
            'end',
            'performanceData',
            'totalSystemGross',
            'totalSystemNet',
            'totalSystemOrders',
            'chartLabels',
            'chartSales',
            'chartOrders'
        ));
    }

    /**
     * =========================================================================
     * 3. TRANSFER STOK ANTAR CABANG
     * URL: /admin/multi-outlet/transfer-stok
     * =========================================================================
     */
    public function transferStok(Request $request)
    {
        $query = StockTransfer::with(['fromOutlet', 'toOutlet', 'requester', 'approver', 'items.warehouseStock']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('outlet_id')) {
            $oid = $request->outlet_id;
            $query->where(function ($q) use ($oid) {
                $q->where('from_outlet_id', $oid)->orWhere('to_outlet_id', $oid);
            });
        }

        $transfers = $query->orderByDesc('id')->get();

        $allOutlets = Outlet::where('status', 'active')->orderBy('name')->get();
        $warehouseItems = CentralWarehouseStock::orderBy('item_name')->get();

        $pendingCount = StockTransfer::where('status', 'Pending')->count();
        $inTransitCount = StockTransfer::where('status', 'In Transit')->count();
        $completedCount = StockTransfer::where('status', 'Completed')->count();
        $totalTransfers = StockTransfer::count();

        return view('admin.multi_outlet.transfer_stok', compact(
            'transfers',
            'allOutlets',
            'warehouseItems',
            'pendingCount',
            'inTransitCount',
            'completedCount',
            'totalTransfers'
        ));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'to_outlet_id' => 'required|exists:outlets,id',
            'transfer_date' => 'required|date',
            'item_names' => 'required|array|min:1',
            'item_names.*' => 'required|string',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|numeric|min:0.1',
            'units' => 'required|array|min:1',
            'units.*' => 'required|string',
        ]);

        $prefix = 'TRF-' . date('Ymd');
        $countToday = StockTransfer::whereDate('created_at', today())->count() + 1;
        $transferNumber = $prefix . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        $transfer = StockTransfer::create([
            'transfer_number' => $transferNumber,
            'from_outlet_id' => $request->from_outlet_id ?: null, // null means Gudang Pusat
            'to_outlet_id' => $request->to_outlet_id,
            'requested_by' => Auth::id(),
            'approved_by' => null,
            'status' => 'Pending',
            'transfer_date' => $request->transfer_date,
            'notes' => $request->notes,
        ]);

        foreach ($request->item_names as $idx => $itemName) {
            $whStockId = $request->warehouse_stock_ids[$idx] ?? null;
            $qty = (float) $request->quantities[$idx];
            $unit = $request->units[$idx];
            $itemNote = $request->item_notes[$idx] ?? null;

            StockTransferItem::create([
                'stock_transfer_id' => $transfer->id,
                'warehouse_stock_id' => $whStockId ?: null,
                'item_name' => $itemName,
                'quantity' => $qty,
                'unit' => $unit,
                'notes' => $itemNote,
            ]);
        }

        return back()->with('success', "Permintaan Transfer Stok #{$transferNumber} berhasil dibuat!");
    }

    public function updateStatusTransfer(Request $request, StockTransfer $transfer)
    {
        $request->validate([
            'status' => 'required|in:Pending,In Transit,Completed,Cancelled',
        ]);

        $oldStatus = $transfer->status;
        $newStatus = $request->status;

        $updateData = ['status' => $newStatus];

        if ($newStatus === 'Completed' || $newStatus === 'In Transit') {
            $updateData['approved_by'] = Auth::id();
        }

        // Potong stok gudang pusat jika status berubah menjadi Completed dan transfer berasal dari Gudang Pusat (from_outlet_id null / is_central_warehouse)
        if ($oldStatus !== 'Completed' && $newStatus === 'Completed') {
            foreach ($transfer->items as $item) {
                if ($item->warehouse_stock_id) {
                    $whStock = CentralWarehouseStock::find($item->warehouse_stock_id);
                    if ($whStock) {
                        $whStock->decrement('stock_quantity', $item->quantity);
                    }
                }
            }
        }

        $transfer->update($updateData);

        return back()->with('success', "Status Transfer #{$transfer->transfer_number} berhasil diperbarui menjadi '{$newStatus}'!");
    }

    /**
     * =========================================================================
     * 4. GUDANG PUSAT (Central Warehouse Inventory)
     * URL: /admin/multi-outlet/gudang-pusat
     * =========================================================================
     */
    public function gudangPusat(Request $request)
    {
        $query = CentralWarehouseStock::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('supplier', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filter === 'low_stock') {
            $query->whereColumn('stock_quantity', '<=', 'min_stock');
        }

        $items = $query->orderBy('item_name')->get();

        $categories = CentralWarehouseStock::distinct('category')->pluck('category');

        $totalItems = CentralWarehouseStock::count();
        $lowStockCount = CentralWarehouseStock::whereColumn('stock_quantity', '<=', 'min_stock')->count();
        $totalAssetValue = CentralWarehouseStock::all()->sum(fn($i) => $i->stock_quantity * $i->unit_cost);

        // Recent Central Outgoing Transfers
        $recentTransfers = StockTransfer::whereNull('from_outlet_id')
            ->orWhereHas('fromOutlet', fn($q) => $q->where('is_central_warehouse', true))
            ->with(['toOutlet', 'items'])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('admin.multi_outlet.gudang_pusat', compact(
            'items',
            'categories',
            'totalItems',
            'lowStockCount',
            'totalAssetValue',
            'recentTransfers'
        ));
    }

    public function storeWarehouseStock(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:central_warehouse_stocks,sku',
            'category' => 'required|string|max:100',
            'stock_quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:30',
            'min_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        CentralWarehouseStock::create([
            'item_name' => $request->item_name,
            'sku' => strtoupper($request->sku),
            'category' => $request->category,
            'stock_quantity' => $request->stock_quantity,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock,
            'unit_cost' => $request->unit_cost,
            'supplier' => $request->supplier,
            'notes' => $request->notes,
        ]);

        return back()->with('success', "Item Gudang Pusat '{$request->item_name}' berhasil ditambahkan!");
    }

    public function updateWarehouseStock(Request $request, CentralWarehouseStock $stock)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'sku' => "required|string|max:50|unique:central_warehouse_stocks,sku,{$stock->id}",
            'category' => 'required|string|max:100',
            'stock_quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:30',
            'min_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $stock->update([
            'item_name' => $request->item_name,
            'sku' => strtoupper($request->sku),
            'category' => $request->category,
            'stock_quantity' => $request->stock_quantity,
            'unit' => $request->unit,
            'min_stock' => $request->min_stock,
            'unit_cost' => $request->unit_cost,
            'supplier' => $request->supplier,
            'notes' => $request->notes,
        ]);

        return back()->with('success', "Item Gudang Pusat '{$stock->item_name}' berhasil diperbarui!");
    }

    public function restockWarehouseStock(Request $request, CentralWarehouseStock $stock)
    {
        $request->validate([
            'added_quantity' => 'required|numeric|min:0.1',
            'unit_cost' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $stock->increment('stock_quantity', $request->added_quantity);

        if ($request->filled('unit_cost') && $request->unit_cost > 0) {
            $stock->update(['unit_cost' => $request->unit_cost]);
        }
        if ($request->filled('supplier')) {
            $stock->update(['supplier' => $request->supplier]);
        }

        return back()->with('success', "Restok item '{$stock->item_name}' berhasil sebanyak +{$request->added_quantity} {$stock->unit}!");
    }

    public function destroyWarehouseStock(CentralWarehouseStock $stock)
    {
        $stock->delete();
        return back()->with('success', "Item '{$stock->item_name}' berhasil dihapus dari inventori gudang pusat!");
    }

    /**
     * =========================================================================
     * 5. HAK AKSES PER CABANG
     * URL: /admin/multi-outlet/hak-akses
     * =========================================================================
     */
    public function hakAkses(Request $request)
    {
        $query = User::with('outlet');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('outlet_id')) {
            if ($request->outlet_id === 'all') {
                $query->whereNull('outlet_id');
            } else {
                $query->where('outlet_id', $request->outlet_id);
            }
        }

        $users = $query->orderBy('name')->get();

        $allOutlets = Outlet::where('status', 'active')->orderBy('name')->get();

        $totalUsers = User::count();
        $assignedUsersCount = User::whereNotNull('outlet_id')->count();
        $allBranchAccessCount = User::whereNull('outlet_id')->count();

        return view('admin.multi_outlet.hak_akses', compact(
            'users',
            'allOutlets',
            'totalUsers',
            'assignedUsersCount',
            'allBranchAccessCount'
        ));
    }

    public function assignOutlet(Request $request, User $user)
    {
        $request->validate([
            'outlet_id' => 'nullable|exists:outlets,id',
        ]);

        $user->update([
            'outlet_id' => $request->outlet_id ?: null,
        ]);

        $outletName = $user->outlet ? $user->outlet->name : 'Semua Cabang (Akses Kantor Pusat / Owner)';

        return back()->with('success', "Hak akses cabang untuk '{$user->name}' berhasil diperbarui ke: {$outletName}!");
    }
}

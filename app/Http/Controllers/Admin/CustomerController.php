<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPointLog;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Redirect default /admin/customers ke menu Database Pelanggan
     */
    public function index(Request $request)
    {
        return $this->databasePelanggan($request);
    }

    /**
     * 1. SUB-MENU: DATABASE PELANGGAN
     * URL: /admin/customers/database-pelanggan
     */
    public function databasePelanggan(Request $request)
    {
        $query = Customer::query();

        // Pencarian (Nama, No HP, Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter Tier
        if ($request->filled('tier') && in_array($request->tier, ['Bronze', 'Silver', 'Gold', 'Platinum'])) {
            $query->where('tier', $request->tier);
        }

        // Pengurutan
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'spending_desc' => $query->orderByDesc('total_spending'),
            'points_desc' => $query->orderByDesc('points'),
            'visits_desc' => $query->orderByDesc('visit_count'),
            'name_asc' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $customers = $query->paginate(15)->withQueryString();

        // Statistik
        $totalCustomers = Customer::count();
        $totalWithPhone = Customer::whereNotNull('phone')->count();
        $totalWithEmail = Customer::whereNotNull('email')->where('email', '!=', '')->count();
        $totalPoints = Customer::sum('points');

        return view('admin.customers.database', compact(
            'customers',
            'totalCustomers',
            'totalWithPhone',
            'totalWithEmail',
            'totalPoints'
        ));
    }

    /**
     * 2. SUB-MENU: RIWAYAT PEMBELIAN PELANGGAN
     * URL: /admin/customers/riwayat-pembelian
     */
    public function riwayatPembelian(Request $request)
    {
        $query = Order::with('details.menu')->latest();

        // Filter Pelanggan spesifik
        if ($request->filled('customer_phone')) {
            $query->where('phone', $request->customer_phone);
        }

        // Pencarian nomor order atau nama/hp
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        // Filter Status Pesanan
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Summary KPI
        $allOrdersQuery = Order::query();
        $totalTransactions = $allOrdersQuery->count();
        $totalRevenue = $allOrdersQuery->where('status', '!=', 'Cancelled')->sum('total');
        $avgSpending = $totalTransactions > 0 ? round($totalRevenue / $totalTransactions) : 0;

        $customersList = Customer::orderBy('name')->select('id', 'name', 'phone', 'tier')->get();

        return view('admin.customers.riwayat_pembelian', compact(
            'orders',
            'totalTransactions',
            'totalRevenue',
            'avgSpending',
            'customersList'
        ));
    }

    /**
     * 3. SUB-MENU: MEMBERSHIP TIER (Bronze · Silver · Gold · Platinum)
     * URL: /admin/customers/membership-tier
     */
    public function membershipTier(Request $request)
    {
        $tierCounts = [
            'Bronze' => Customer::where('tier', 'Bronze')->count(),
            'Silver' => Customer::where('tier', 'Silver')->count(),
            'Gold' => Customer::where('tier', 'Gold')->count(),
            'Platinum' => Customer::where('tier', 'Platinum')->count(),
        ];

        $totalMembers = array_sum($tierCounts);

        $selectedTier = $request->get('tier', 'All');
        $query = Customer::query();

        if (in_array($selectedTier, ['Bronze', 'Silver', 'Gold', 'Platinum'])) {
            $query->where('tier', $selectedTier);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $members = $query->orderByDesc('total_spending')->paginate(15)->withQueryString();

        return view('admin.customers.membership_tier', compact(
            'tierCounts',
            'totalMembers',
            'members',
            'selectedTier'
        ));
    }

    /**
     * Update Tier Member Manual
     */
    public function updateMemberTier(Request $request, Customer $customer)
    {
        $request->validate([
            'tier' => 'required|in:Bronze,Silver,Gold,Platinum'
        ]);

        $customer->update(['tier' => $request->tier]);

        return back()->with('success', "Membership Tier {$customer->name} berhasil diubah ke {$request->tier}.");
    }

    /**
     * 4. SUB-MENU: POIN PELANGGAN
     * URL: /admin/customers/poin-pelanggan
     */
    public function poinPelanggan(Request $request)
    {
        // Top 5 Poin Tertinggi (Leaderboard)
        $topMembers = Customer::orderByDesc('points')->limit(5)->get();

        // Mutasi Poin Log
        $logsQuery = CustomerPointLog::with('customer')->latest();

        if ($request->filled('type')) {
            $logsQuery->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $logsQuery->whereHas('customer', function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $pointLogs = $logsQuery->paginate(15)->withQueryString();

        // KPI
        $totalCirculating = Customer::sum('points');
        $totalEarned = CustomerPointLog::where('points', '>', 0)->sum('points');
        $totalRedeemed = abs(CustomerPointLog::where('type', 'redeemed')->sum('points'));

        $customersList = Customer::orderBy('name')->select('id', 'name', 'phone', 'points')->get();

        return view('admin.customers.poin_pelanggan', compact(
            'topMembers',
            'pointLogs',
            'totalCirculating',
            'totalEarned',
            'totalRedeemed',
            'customersList'
        ));
    }

    /**
     * 5. SUB-MENU: MENU FAVORIT PELANGGAN
     * URL: /admin/customers/menu-favorit
     */
    public function menuFavorit(Request $request)
    {
        // Top 10 Menu Paling Favorit Di Seluruh Pelanggan
        $topMenus = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->leftJoin('categories', 'menus.category_id', '=', 'categories.id')
            ->where('orders.status', '!=', 'Cancelled')
            ->select(
                'menus.id',
                'menus.name',
                'menus.image',
                'menus.price',
                'categories.name as category_name',
                DB::raw('SUM(order_details.qty) as total_sold'),
                DB::raw('SUM(order_details.total) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.phone) as unique_customers')
            )
            ->groupBy('menus.id', 'menus.name', 'menus.image', 'menus.price', 'categories.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Daftar Pelanggan beserta Menu Favorit Masing-masing
        $customers = Customer::orderByDesc('visit_count')->paginate(10);
        $customerFavorites = [];

        foreach ($customers as $c) {
            $customerFavorites[$c->id] = $c->favoriteMenus(3);
        }

        return view('admin.customers.menu_favorit', compact(
            'topMenus',
            'customers',
            'customerFavorites'
        ));
    }

    /**
     * 6. SUB-MENU: BIRTHDAY REMINDER
     * URL: /admin/customers/birthday-reminder
     */
    public function birthdayReminder(Request $request)
    {
        $today = Carbon::today();

        // Hari Ini
        $birthdaysToday = Customer::whereNotNull('birth_date')
            ->whereMonth('birth_date', $today->month)
            ->whereDay('birth_date', $today->day)
            ->orderBy('name')
            ->get();

        // 7 Hari Kedepan
        $start = $today->copy();
        $end = $today->copy()->addDays(7);

        $upcomingBirthdays = Customer::whereNotNull('birth_date')
            ->get()
            ->filter(function ($cust) use ($start, $end) {
                $bdayThisYear = Carbon::createFromDate(
                    $start->year,
                    $cust->birth_date->month,
                    $cust->birth_date->day
                );
                return $bdayThisYear->betweenIncluded($start, $end);
            })
            ->sortBy(function ($cust) use ($start) {
                return Carbon::createFromDate($start->year, $cust->birth_date->month, $cust->birth_date->day);
            });

        // Bulan Ini
        $birthdaysThisMonth = Customer::whereNotNull('birth_date')
            ->whereMonth('birth_date', $today->month)
            ->orderByRaw('DAY(birth_date) ASC')
            ->get();

        return view('admin.customers.birthdays', compact(
            'birthdaysToday',
            'upcomingBirthdays',
            'birthdaysThisMonth',
            'today'
        ));
    }

    /**
     * Form Tambah Pelanggan Baru
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Simpan Pelanggan Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
            'tier' => 'required|in:Bronze,Silver,Gold,Platinum',
            'initial_points' => 'nullable|integer|min:0',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $initialPoints = (int) ($request->initial_points ?? 0);

        $customer = Customer::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'tier' => $validated['tier'],
            'points' => $initialPoints,
            'address' => $validated['address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'visit_count' => 0,
            'total_spending' => 0,
            'last_visit' => null,
        ]);

        if ($initialPoints > 0) {
            $customer->pointLogs()->create([
                'points' => $initialPoints,
                'type' => 'earned',
                'description' => 'Poin awal saat pendaftaran member',
            ]);
        }

        return redirect()
            ->route('admin.customers.show', $customer->id)
            ->with('success', "Pelanggan {$customer->name} berhasil ditambahkan ke CRM!");
    }

    /**
     * Detail Profil 360 Pelanggan
     */
    public function show(Customer $customer)
    {
        $orders = Order::with('details.menu')
            ->where('phone', $customer->phone)
            ->latest()
            ->paginate(10);

        $favoriteMenus = $customer->favoriteMenus(8);
        $pointLogs = $customer->pointLogs()->paginate(10, ['*'], 'point_page');

        $cleanPhone = preg_replace('/[^0-9]/', '', $customer->phone);
        $waPhone = str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone;

        $waGreetingUrl = "https://wa.me/{$waPhone}?text=" . urlencode("Halo Kak {$customer->name}, terima kasih telah menjadi pelanggan setia Cafe POS! Saat ini Kakak berada di Membership {$customer->tier} dengan {$customer->points} Poin Loyalty. Nikmati penawaran spesial untuk kunjungan berikutnya!");
        $waBirthdayUrl = "https://wa.me/{$waPhone}?text=" . urlencode("Selamat Ulang Tahun Kak {$customer->name}! 🎂🎉 Spesial di hari bahagia ini, Cafe POS memberikan promo istimewa diskon 20% & traktiran minuman untuk Kakak. Tunjukkan pesan ini ke kasir kami ya!");

        return view('admin.customers.show', compact(
            'customer',
            'orders',
            'favoriteMenus',
            'pointLogs',
            'waGreetingUrl',
            'waBirthdayUrl'
        ));
    }

    /**
     * Form Edit Pelanggan
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Perbarui Data Pelanggan
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:customers,phone,' . $customer->id,
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable|date',
            'tier' => 'required|in:Bronze,Silver,Gold,Platinum',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('admin.customers.show', $customer->id)
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Hapus Pelanggan
     */
    public function destroy(Customer $customer)
    {
        $name = $customer->name;
        $customer->delete();

        return redirect()
            ->route('admin.customers.database')
            ->with('success', "Data pelanggan {$name} berhasil dihapus.");
    }

    /**
     * Penyesuaian Poin Manual (Tambah/Kurang)
     */
    public function adjustPoints(Request $request, Customer $customer)
    {
        $request->validate([
            'points' => 'required|integer',
            'type' => 'required|in:earned,redeemed,adjusted',
            'description' => 'required|string|max:255',
        ]);

        $points = (int) $request->points;

        if ($request->type === 'redeemed' && $points > 0) {
            $points = -$points;
        }

        if ($points < 0 && abs($points) > $customer->points) {
            return back()->with('error', "Poin pelanggan tidak mencukupi untuk dikurangi. Saldo saat ini: {$customer->points} poin.");
        }

        $customer->points += $points;
        $customer->save();

        CustomerPointLog::create([
            'customer_id' => $customer->id,
            'points' => $points,
            'type' => $request->type,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Penyesuaian poin pelanggan berhasil disimpan.');
    }

    /**
     * Kalkulasi Ulang Membership Tier Otomatis
     */
    public function recalculateTiers()
    {
        $customers = Customer::all();
        $updated = 0;

        foreach ($customers as $customer) {
            $newTier = $customer->calculateTier();
            if ($customer->tier !== $newTier) {
                $customer->update(['tier' => $newTier]);
                $updated++;
            }
        }

        return back()->with('success', "Proses kalkulasi selesai! {$updated} pelanggan berhasil disinkronisasi tier-nya.");
    }

    /**
     * Ekspor Data ke CSV
     */
    public function exportCsv()
    {
        $customers = Customer::latest()->get();
        $filename = 'Database_Pelanggan_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($customers) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'ID',
                'Nama',
                'No Telepon',
                'Email',
                'Tanggal Lahir',
                'Membership Tier',
                'Poin Loyalty',
                'Total Kunjungan',
                'Total Belanja (Rp)',
                'Kunjungan Terakhir',
                'Catatan Preferensi'
            ], ';');

            foreach ($customers as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->name,
                    $c->phone,
                    $c->email ?? '-',
                    $c->birth_date ? $c->birth_date->format('d/m/Y') : '-',
                    $c->tier,
                    $c->points,
                    $c->visit_count,
                    number_format($c->total_spending, 0, ',', '.'),
                    $c->last_visit ? $c->last_visit->format('d/m/Y H:i') : '-',
                    $c->notes ?? '-'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
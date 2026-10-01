<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\PointReward;
use App\Models\PointRewardClaim;
use App\Models\StampProgram;
use App\Models\CustomerStamp;
use App\Models\StampLog;
use App\Models\CashbackRule;
use App\Models\CashbackLog;
use App\Models\Referral;
use App\Models\Customer;
use App\Models\CustomerPointLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoLoyaltyController extends Controller
{
    /**
     * =========================================================================
     * 1. VOUCHER & KUPON PROMO (Fitur 1 & 2: Persen/Nominal, Min Belanja, Masa Aktif)
     * =========================================================================
     */
    public function vouchers(Request $request)
    {
        $query = Voucher::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('code', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $today = Carbon::today();
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where('start_date', '<=', $today)
                      ->where('end_date', '>=', $today);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', $today);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $vouchers = $query->paginate(12)->withQueryString();

        // KPI Voucher
        $totalVouchers = Voucher::count();
        $activeVouchers = Voucher::where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->count();
        $totalUsedCount = Voucher::sum('used_count');

        // Recent Usages
        $recentUsages = VoucherUsage::with(['voucher', 'customer', 'order'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.promo.vouchers', compact(
            'vouchers',
            'totalVouchers',
            'activeVouchers',
            'totalUsedCount',
            'recentUsages'
        ));
    }

    public function storeVoucher(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'max_discount' => 'nullable|numeric|min:0',
            'min_spending' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $validated['code'] = strtoupper(str_replace(' ', '', $validated['code']));
        $validated['min_spending'] = $validated['min_spending'] ?? 0;
        $validated['is_active'] = true;

        Voucher::create($validated);

        return redirect()->route('admin.promo.vouchers')
            ->with('success', "Voucher '{$validated['code']}' berhasil diterbitkan!");
    }

    public function updateVoucher(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'max_discount' => 'nullable|numeric|min:0',
            'min_spending' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $validated['code'] = strtoupper(str_replace(' ', '', $validated['code']));
        $voucher->update($validated);

        return redirect()->route('admin.promo.vouchers')
            ->with('success', "Voucher '{$voucher->code}' berhasil diperbarui.");
    }

    public function toggleVoucher(Voucher $voucher)
    {
        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Voucher {$voucher->code} berhasil {$status}.");
    }

    public function destroyVoucher(Voucher $voucher)
    {
        $code = $voucher->code;
        $voucher->delete();

        return redirect()->route('admin.promo.vouchers')
            ->with('success', "Voucher {$code} berhasil dihapus.");
    }

    /**
     * =========================================================================
     * 2. POINT REWARD (Fitur 3: Katalog Hadiah & Penukaran Poin)
     * =========================================================================
     */
    public function pointRewards(Request $request)
    {
        $rewards = PointReward::latest()->paginate(9);
        $customers = Customer::orderBy('name')->get();

        $claims = PointRewardClaim::with(['reward', 'customer'])
            ->latest()
            ->paginate(15, ['*'], 'claims_page');

        // KPI
        $totalItems = PointReward::count();
        $totalClaims = PointRewardClaim::count();
        $totalPointsSpent = PointRewardClaim::sum('points_spent');

        return view('admin.promo.point_rewards', compact(
            'rewards',
            'claims',
            'customers',
            'totalItems',
            'totalClaims',
            'totalPointsSpent'
        ));
    }

    public function storePointReward(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_required' => 'required|integer|min:1',
            'reward_type' => 'required|in:product,discount,merchandise',
            'discount_value' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rewards', 'public');
        }

        PointReward::create($validated);

        return redirect()->route('admin.promo.point-rewards')
            ->with('success', 'Item Reward baru berhasil ditambahkan ke katalog!');
    }

    public function updatePointReward(Request $request, PointReward $pointReward)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'points_required' => 'required|integer|min:1',
            'reward_type' => 'required|in:product,discount,merchandise',
            'discount_value' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rewards', 'public');
        }

        $pointReward->update($validated);

        return redirect()->route('admin.promo.point-rewards')
            ->with('success', 'Item Reward berhasil diperbarui.');
    }

    public function destroyPointReward(PointReward $pointReward)
    {
        $pointReward->delete();
        return redirect()->route('admin.promo.point-rewards')
            ->with('success', 'Item Reward berhasil dihapus.');
    }

    public function claimReward(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'point_reward_id' => 'required|exists:point_rewards,id',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $reward = PointReward::findOrFail($request->point_reward_id);

        if ($reward->stock < 1) {
            return back()->with('error', "Stok hadiah '{$reward->name}' sedang habis.");
        }

        if ($customer->points < $reward->points_required) {
            return back()->with('error', "Poin {$customer->name} ({$customer->points} Pts) tidak mencukupi untuk menukar {$reward->name} ({$reward->points_required} Pts).");
        }

        // Potong Poin Pelanggan
        $customer->points -= $reward->points_required;
        $customer->save();

        // Kurangi Stok Reward
        $reward->decrement('stock');

        // Buat Kode Klaim Unik
        $claimCode = 'CLM-' . strtoupper(Str::random(6));

        $claim = PointRewardClaim::create([
            'point_reward_id' => $reward->id,
            'customer_id' => $customer->id,
            'points_spent' => $reward->points_required,
            'claim_code' => $claimCode,
            'status' => 'claimed',
            'notes' => $request->notes,
        ]);

        // Catat di CustomerPointLog
        CustomerPointLog::create([
            'customer_id' => $customer->id,
            'points' => -$reward->points_required,
            'type' => 'redeemed',
            'description' => "Penukaran hadiah '{$reward->name}' (Kode: {$claimCode})",
        ]);

        return redirect()->route('admin.promo.point-rewards')
            ->with('success', "Penukaran berhasil! Kode Klaim: {$claimCode} untuk {$customer->name}.");
    }

    public function updateClaimStatus(Request $request, PointRewardClaim $claim)
    {
        $request->validate([
            'status' => 'required|in:claimed,used,cancelled',
        ]);

        $claim->update(['status' => $request->status]);

        return back()->with('success', "Status klaim hadiah #{$claim->claim_code} diperbarui ke '{$request->status}'.");
    }

    /**
     * =========================================================================
     * 3. STAMP CARD DIGITAL (Fitur 4: Kartu Stamp Digital)
     * =========================================================================
     */
    public function stampCards(Request $request)
    {
        // Program Stamp Utama (Default 10 stamp = Free Beverage)
        $program = StampProgram::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Coffee Lovers Stamp Card',
                'target_stamps' => 10,
                'min_purchase' => 25000,
                'reward_description' => 'Gratis 1 Minuman Pilihan (Regular/Large)',
                'is_active' => true,
            ]
        );

        $customers = Customer::orderBy('name')->get();

        // Customer Stamps
        $query = CustomerStamp::with(['customer', 'program'])->where('stamp_program_id', $program->id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('customer', function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $customerStamps = $query->paginate(12)->withQueryString();

        // Recent Stamp Logs
        $stampLogs = StampLog::with(['customer'])
            ->where('stamp_program_id', $program->id)
            ->latest()
            ->limit(10)
            ->get();

        // Summary KPI
        $totalActiveCards = CustomerStamp::where('stamp_program_id', $program->id)->count();
        $totalStampsIssued = StampLog::where('stamp_program_id', $program->id)->where('action', 'add')->sum('stamps');
        $totalCompletedRewards = CustomerStamp::where('stamp_program_id', $program->id)->sum('total_completed_cards');

        return view('admin.promo.stamp_cards', compact(
            'program',
            'customerStamps',
            'customers',
            'stampLogs',
            'totalActiveCards',
            'totalStampsIssued',
            'totalCompletedRewards'
        ));
    }

    public function updateStampProgram(Request $request, StampProgram $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_stamps' => 'required|integer|min:3|max:20',
            'min_purchase' => 'required|numeric|min:0',
            'reward_description' => 'required|string|max:255',
        ]);

        $program->update($validated);

        return back()->with('success', 'Konfigurasi Stamp Card Digital berhasil diperbarui.');
    }

    public function addCustomerStamp(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'stamps' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string',
        ]);

        $program = StampProgram::firstOrCreate(
            ['id' => 1],
            ['name' => 'Coffee Lovers Stamp Card', 'target_stamps' => 10, 'reward_description' => 'Free 1 Beverage']
        );

        $customerStamp = CustomerStamp::firstOrCreate(
            [
                'customer_id' => $request->customer_id,
                'stamp_program_id' => $program->id,
            ],
            [
                'current_stamps' => 0,
                'total_completed_cards' => 0,
            ]
        );

        $added = (int) $request->stamps;
        $newTotal = $customerStamp->current_stamps + $added;
        $completedCards = 0;

        while ($newTotal >= $program->target_stamps) {
            $newTotal -= $program->target_stamps;
            $completedCards++;
        }

        $customerStamp->current_stamps = $newTotal;
        $customerStamp->total_completed_cards += $completedCards;
        $customerStamp->save();

        StampLog::create([
            'customer_id' => $request->customer_id,
            'stamp_program_id' => $program->id,
            'stamps' => $added,
            'action' => 'add',
            'description' => $request->description ?? "Penambahan {$added} stamp belanja",
        ]);

        $msg = "Berhasil menambahkan {$added} stamp untuk {$customerStamp->customer->name}!";
        if ($completedCards > 0) {
            $msg .= " 🎉 Pelanggan telah melengkapi kartu stamp ({$completedCards}x reward siap diklaim)!";
        }

        return back()->with('success', $msg);
    }

    public function redeemCustomerStamp(Request $request, CustomerStamp $customerStamp)
    {
        if ($customerStamp->total_completed_cards < 1) {
            return back()->with('error', 'Pelanggan belum memiliki kartu stamp penuh yang dapat diklaim.');
        }

        $customerStamp->decrement('total_completed_cards');

        StampLog::create([
            'customer_id' => $customerStamp->customer_id,
            'stamp_program_id' => $customerStamp->stamp_program_id,
            'stamps' => -$customerStamp->program->target_stamps,
            'action' => 'redeem',
            'description' => "Klaim reward kartu stamp penuh: '{$customerStamp->program->reward_description}'",
        ]);

        return back()->with('success', "Reward stamp card untuk {$customerStamp->customer->name} berhasil diklaim!");
    }

    /**
     * =========================================================================
     * 4. PROGRAM CASHBACK (Fitur 5: Aturan Cashback & Riwayat)
     * =========================================================================
     */
    public function cashback(Request $request)
    {
        $rules = CashbackRule::latest()->get();
        $customers = Customer::orderBy('name')->get();

        $query = CashbackLog::with(['rule', 'customer'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('customer', function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $cashbackLogs = $query->paginate(15)->withQueryString();

        // KPI
        $totalRules = CashbackRule::where('is_active', true)->count();
        $totalCashbackGiven = CashbackLog::sum('amount');
        $totalCashbackPoints = CashbackLog::sum('points_rewarded');

        return view('admin.promo.cashback', compact(
            'rules',
            'cashbackLogs',
            'customers',
            'totalRules',
            'totalCashbackGiven',
            'totalCashbackPoints'
        ));
    }

    public function storeCashbackRule(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:1',
            'reward_as' => 'required|in:points,balance_discount',
            'min_spending' => 'nullable|numeric|min:0',
            'max_cashback' => 'nullable|numeric|min:0',
            'tier_eligibility' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['min_spending'] = $validated['min_spending'] ?? 0;
        $validated['is_active'] = true;

        CashbackRule::create($validated);

        return redirect()->route('admin.promo.cashback')
            ->with('success', "Program Cashback '{$validated['name']}' berhasil dibuat!");
    }

    public function toggleCashbackRule(CashbackRule $rule)
    {
        $rule->is_active = !$rule->is_active;
        $rule->save();

        $status = $rule->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Program cashback '{$rule->name}' {$status}.");
    }

    public function destroyCashbackRule(CashbackRule $rule)
    {
        $name = $rule->name;
        $rule->delete();
        return back()->with('success', "Program cashback '{$name}' berhasil dihapus.");
    }

    public function grantCashback(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'cashback_rule_id' => 'nullable|exists:cashback_rules,id',
            'amount' => 'required|numeric|min:1000',
            'notes' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $amount = (float) $request->amount;

        // Reward diberikan dalam bentuk Poin Loyalty (Rp 1.000 cashback = 1 Poin atau sesuai aturan)
        $points = (int) floor($amount / 1000);

        $customer->points += $points;
        $customer->save();

        CustomerPointLog::create([
            'customer_id' => $customer->id,
            'points' => $points,
            'type' => 'earned',
            'description' => "Reward Cashback Promo Rp " . number_format($amount, 0, ',', '.'),
        ]);

        CashbackLog::create([
            'cashback_rule_id' => $request->cashback_rule_id,
            'customer_id' => $customer->id,
            'amount' => $amount,
            'points_rewarded' => $points,
            'notes' => $request->notes ?? 'Pemberian cashback transaksi',
        ]);

        return back()->with('success', "Cashback Rp " . number_format($amount, 0, ',', '.') . " (+{$points} Pts) berhasil diberikan kepada {$customer->name}!");
    }

    /**
     * =========================================================================
     * 5. REFERRAL PROGRAM (Fitur 6: Member-Get-Member)
     * =========================================================================
     */
    public function referrals(Request $request)
    {
        // Pastikan setiap customer memiliki referral_code
        $customersWithoutCode = Customer::whereNull('referral_code')->get();
        foreach ($customersWithoutCode as $c) {
            $c->getReferralCode();
        }

        $customers = Customer::orderBy('name')->get();

        // Top Referrers
        $topReferrers = Customer::withCount('referrals')
            ->having('referrals_count', '>', 0)
            ->orderByDesc('referrals_count')
            ->limit(5)
            ->get();

        // Daftar Referral
        $referrals = Referral::with(['referrer', 'referee'])
            ->latest()
            ->paginate(15);

        // KPI
        $totalReferrals = Referral::count();
        $totalReferralPointsGiven = Referral::sum('referrer_points_rewarded') + Referral::sum('referee_points_rewarded');

        return view('admin.promo.referrals', compact(
            'customers',
            'topReferrers',
            'referrals',
            'totalReferrals',
            'totalReferralPointsGiven'
        ));
    }

    public function storeReferral(Request $request)
    {
        $request->validate([
            'referrer_code' => 'required|exists:customers,referral_code',
            'referee_name' => 'required|string|max:255',
            'referee_phone' => 'required|string|max:20|unique:customers,phone',
            'referee_email' => 'nullable|email|max:255',
        ]);

        $referrer = Customer::where('referral_code', $request->referrer_code)->firstOrFail();

        // Poin Reward Referral: Referrer dapat 50 Poin, Referee (member baru) dapat 25 Poin
        $referrerBonus = 50;
        $refereeBonus = 25;

        // Buat member baru (referee)
        $referee = Customer::create([
            'name' => $request->referee_name,
            'phone' => $request->referee_phone,
            'email' => $request->referee_email,
            'tier' => 'Bronze',
            'points' => $refereeBonus,
            'referred_by_id' => $referrer->id,
            'visit_count' => 0,
            'total_spending' => 0,
        ]);
        $referee->getReferralCode(); // generate kode untuk dirinya sendiri

        // Tambah poin ke Referrer
        $referrer->points += $referrerBonus;
        $referrer->save();

        // Log Poin Referrer
        CustomerPointLog::create([
            'customer_id' => $referrer->id,
            'points' => $referrerBonus,
            'type' => 'earned',
            'description' => "Bonus Referral berhasil mengajak '{$referee->name}'",
        ]);

        // Log Poin Referee
        CustomerPointLog::create([
            'customer_id' => $referee->id,
            'points' => $refereeBonus,
            'type' => 'earned',
            'description' => "Welcome Bonus Referral dari '{$referrer->name}'",
        ]);

        // Catat Referral
        Referral::create([
            'referrer_id' => $referrer->id,
            'referee_id' => $referee->id,
            'referrer_points_rewarded' => $referrerBonus,
            'referee_points_rewarded' => $refereeBonus,
            'status' => 'rewarded',
        ]);

        return redirect()->route('admin.promo.referrals')
            ->with('success', "Referral sukses! {$referrer->name} (+{$referrerBonus} Pts) dan {$referee->name} (+{$refereeBonus} Pts) telah menerima reward.");
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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

class PromoLoyaltySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Vouchers
        $v1 = Voucher::updateOrCreate(
            ['code' => 'KOPI20'],
            [
                'name' => 'Diskon Kopi 20% Special',
                'type' => 'percentage',
                'discount_value' => 20,
                'max_discount' => 25000,
                'min_spending' => 50000,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(25),
                'usage_limit' => 100,
                'used_count' => 12,
                'is_active' => true,
                'description' => 'Diskon 20% untuk semua menu kopi dengan minimal belanja Rp 50.000.',
            ]
        );

        $v2 = Voucher::updateOrCreate(
            ['code' => 'HEMAT10K'],
            [
                'name' => 'Potongan Langsung Rp 10.000',
                'type' => 'fixed',
                'discount_value' => 10000,
                'max_discount' => null,
                'min_spending' => 45000,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(15),
                'usage_limit' => 50,
                'used_count' => 8,
                'is_active' => true,
                'description' => 'Potongan langsung Rp 10.000 untuk transaksi dine in & take away.',
            ]
        );

        $v3 = Voucher::updateOrCreate(
            ['code' => 'WEEKEND50'],
            [
                'name' => 'Voucher Gajian Akhir Pekan',
                'type' => 'percentage',
                'discount_value' => 30,
                'max_discount' => 40000,
                'min_spending' => 80000,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(7),
                'usage_limit' => 30,
                'used_count' => 5,
                'is_active' => true,
                'description' => 'Promo spesial akhir pekan untuk member kafe.',
            ]
        );

        // 2. Point Rewards
        $r1 = PointReward::updateOrCreate(
            ['name' => 'Free Iced Americano'],
            [
                'description' => 'Tukarkan 30 poin untuk mendapatkan 1 cup Iced Americano dingin segar.',
                'points_required' => 30,
                'reward_type' => 'product',
                'stock' => 50,
                'is_active' => true,
            ]
        );

        $r2 = PointReward::updateOrCreate(
            ['name' => 'Voucher Potongan Rp 25.000'],
            [
                'description' => 'Voucher potongan belanja Rp 25.000 tanpa minimal belanja.',
                'points_required' => 50,
                'reward_type' => 'discount',
                'discount_value' => 25000,
                'stock' => 30,
                'is_active' => true,
            ]
        );

        $r3 = PointReward::updateOrCreate(
            ['name' => 'Tumbler Stainless Eksklusif Cafe POS'],
            [
                'description' => 'Merchandise eksklusif tumbler ramah lingkungan tahan panas/dingin.',
                'points_required' => 120,
                'reward_type' => 'merchandise',
                'stock' => 15,
                'is_active' => true,
            ]
        );

        $r4 = PointReward::updateOrCreate(
            ['name' => 'Free Butter Croissant'],
            [
                'description' => 'Croissant butter renyah lezat pelengkap kopi Anda.',
                'points_required' => 25,
                'reward_type' => 'product',
                'stock' => 40,
                'is_active' => true,
            ]
        );

        // 3. Stamp Program
        $stampProgram = StampProgram::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Coffee Lovers Stamp Card',
                'target_stamps' => 10,
                'min_purchase' => 25000,
                'reward_description' => 'Gratis 1 Minuman Pilihan (Regular / Large)',
                'is_active' => true,
            ]
        );

        // 4. Cashback Rules
        $cb1 = CashbackRule::updateOrCreate(
            ['name' => 'Cashback 10% Weekend'],
            [
                'type' => 'percentage',
                'value' => 10,
                'reward_as' => 'points',
                'min_spending' => 50000,
                'max_cashback' => 15000,
                'tier_eligibility' => 'All',
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(30),
                'is_active' => true,
            ]
        );

        $cb2 = CashbackRule::updateOrCreate(
            ['name' => 'Cashback VIP Rp 10.000 Gold & Platinum'],
            [
                'type' => 'fixed',
                'value' => 10000,
                'reward_as' => 'points',
                'min_spending' => 75000,
                'max_cashback' => null,
                'tier_eligibility' => 'Gold,Platinum',
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(60),
                'is_active' => true,
            ]
        );

        // Hubungkan ke existing customers jika ada
        $customers = Customer::all();
        if ($customers->count() >= 2) {
            $cust1 = $customers[0];
            $cust2 = $customers[1];

            // Setup Referral Codes
            $cust1->getReferralCode();
            $cust2->getReferralCode();

            // Setup Customer Stamps
            CustomerStamp::updateOrCreate(
                ['customer_id' => $cust1->id, 'stamp_program_id' => $stampProgram->id],
                ['current_stamps' => 7, 'total_completed_cards' => 1]
            );

            CustomerStamp::updateOrCreate(
                ['customer_id' => $cust2->id, 'stamp_program_id' => $stampProgram->id],
                ['current_stamps' => 4, 'total_completed_cards' => 0]
            );

            // Stamp Logs
            StampLog::firstOrCreate(
                ['customer_id' => $cust1->id, 'description' => 'Pembelian 2 Cup Caramel Macchiato'],
                [
                    'customer_id' => $cust1->id,
                    'stamp_program_id' => $stampProgram->id,
                    'stamps' => 2,
                    'action' => 'add',
                    'description' => 'Pembelian 2 Cup Caramel Macchiato',
                ]
            );

            // Cashback Log
            CashbackLog::firstOrCreate(
                ['customer_id' => $cust1->id, 'notes' => 'Cashback promo weekend'],
                [
                    'cashback_rule_id' => $cb1->id,
                    'customer_id' => $cust1->id,
                    'amount' => 7500,
                    'points_rewarded' => 7,
                    'notes' => 'Cashback promo weekend',
                ]
            );

            // Point Reward Claim
            PointRewardClaim::firstOrCreate(
                ['claim_code' => 'CLM-TEST01'],
                [
                    'point_reward_id' => $r1->id,
                    'customer_id' => $cust1->id,
                    'points_spent' => 30,
                    'claim_code' => 'CLM-TEST01',
                    'status' => 'claimed',
                    'notes' => 'Penukaran poin di kasir',
                ]
            );

            // Referral
            Referral::firstOrCreate(
                ['referrer_id' => $cust1->id, 'referee_id' => $cust2->id],
                [
                    'referrer_id' => $cust1->id,
                    'referee_id' => $cust2->id,
                    'referrer_points_rewarded' => 50,
                    'referee_points_rewarded' => 25,
                    'status' => 'rewarded',
                ]
            );
        }
    }
}

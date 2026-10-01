<?php

namespace Database\Seeders;

use App\Models\CafeTable;
use App\Models\Ingredient;
use App\Models\MembershipTierRule;
use App\Models\Menu;
use App\Models\Outlet;
use Illuminate\Database\Seeder;

class AdvancedOperationsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED RECIPES & SOP FOR MENUS
        $menus = Menu::all();
        $sampleRecipes = [
            'Caramel Macchiato' => [
                'serving_temp' => 'Iced (4°C) / Hot (80°C)',
                'taste_notes' => 'Sweet, Creamy Vanilla & Rich Espresso Layer',
                'recipe_steps' => "1. Tuang 20ml Sirup Vanila ke dalam cup saji.\n2. Tambahkan 150ml Fresh Milk (hangat untuk Hot, atau dingin + es batu penuh untuk Iced).\n3. Ekstraksi 2 shot (60ml) Espresso Robusta/Arabika blend ke pitcher kecil.\n4. Tuang espresso perlahan di atas susu agar membentuk layer gradasi.\n5. Berikan drizzle saus Caramel melingkar di atas foam susu.",
                'ingredients' => [
                    ['name' => 'Biji Kopi House Blend', 'quantity' => 18, 'unit' => 'gram', 'cost' => 2800],
                    ['name' => 'Fresh Milk UHT', 'quantity' => 150, 'unit' => 'ml', 'cost' => 2900],
                    ['name' => 'Sirup Caramel & Vanilla', 'quantity' => 25, 'unit' => 'ml', 'cost' => 2500],
                    ['name' => 'Cup + Lid + Straw', 'quantity' => 1, 'unit' => 'pcs', 'cost' => 1200],
                ]
            ],
            'Cafe Latte' => [
                'serving_temp' => 'Hot (65°C - 70°C)',
                'taste_notes' => 'Silky Milk Texture with Nutty Coffee Finish',
                'recipe_steps' => "1. Grind 18g biji kopi halus, ratakan dengan distributor dan tamping 15kg pressure.\n2. Ekstraksi espresso 30ml (waktu ekstraksi 25-28 detik).\n3. Steam 200ml Fresh Milk hingga suhu 65°C dengan micro-foam lembut (tebal 1cm).\n4. Pour susu ke cangkir dengan teknik free-pour latte art (Tulip / Rosetta).",
                'ingredients' => [
                    ['name' => 'Biji Kopi Arabika', 'quantity' => 18, 'unit' => 'gram', 'cost' => 3100],
                    ['name' => 'Fresh Milk UHT', 'quantity' => 200, 'unit' => 'ml', 'cost' => 3800],
                    ['name' => 'Paper Cup / Ceramic Mug', 'quantity' => 1, 'unit' => 'pcs', 'cost' => 950],
                ]
            ],
            'Matcha Latte' => [
                'serving_temp' => 'Iced (5°C)',
                'taste_notes' => 'Earthy Umami with Sweet Milk Balance',
                'recipe_steps' => "1. Timbang 6g Pure Uji Matcha Powder ke dalam chawan.\n2. Larutkan dengan 40ml air hangat (80°C), whisk dengan chasen hingga berbusa halus.\n3. Masukkan 15ml simple syrup dan 160ml oat milk ke dalam cup berisi es batu.\n4. Tuangkan matcha whisked di atas susu secara perlahan.",
                'ingredients' => [
                    ['name' => 'Pure Matcha Powder', 'quantity' => 6, 'unit' => 'gram', 'cost' => 4500],
                    ['name' => 'Oat Milk Barista', 'quantity' => 160, 'unit' => 'ml', 'cost' => 4200],
                    ['name' => 'Simple Syrup', 'quantity' => 15, 'unit' => 'ml', 'cost' => 500],
                    ['name' => 'Cup Takeaway', 'quantity' => 1, 'unit' => 'pcs', 'cost' => 1200],
                ]
            ]
        ];

        foreach ($menus as $m) {
            foreach ($sampleRecipes as $nameKey => $rcp) {
                if (stripos($m->name, $nameKey) !== false || $m->id <= 3) {
                    $m->update([
                        'serving_temp' => $rcp['serving_temp'],
                        'taste_notes' => $rcp['taste_notes'],
                        'recipe_steps' => $rcp['recipe_steps'],
                    ]);

                    // Seed recipe ingredients if empty
                    if ($m->recipeIngredients()->count() === 0) {
                        foreach ($rcp['ingredients'] as $ing) {
                            Ingredient::create([
                                'menu_id' => $m->id,
                                'name' => $ing['name'],
                                'quantity' => $ing['quantity'],
                                'unit' => $ing['unit'],
                                'cost' => $ing['cost'],
                            ]);
                        }
                    }
                    break;
                }
            }
        }

        // 2. SEED CAFE TABLES (QR Table Ordering)
        $outlet1 = Outlet::first();
        $tables = [
            ['table_number' => 'Meja A01', 'capacity' => 2, 'zone' => 'Indoor AC', 'status' => 'available'],
            ['table_number' => 'Meja A02', 'capacity' => 2, 'zone' => 'Indoor AC', 'status' => 'occupied', 'current_customer' => 'Meja 2 (Bpk. Denny)'],
            ['table_number' => 'Meja A03', 'capacity' => 4, 'zone' => 'Indoor AC', 'status' => 'available'],
            ['table_number' => 'Meja A04', 'capacity' => 4, 'zone' => 'Indoor AC', 'status' => 'available'],
            ['table_number' => 'Meja A05', 'capacity' => 6, 'zone' => 'Indoor AC (Sofa)', 'status' => 'reserved', 'current_customer' => 'Reservasi Jam 19:30'],
            ['table_number' => 'Meja O01', 'capacity' => 4, 'zone' => 'Outdoor Terrace', 'status' => 'available'],
            ['table_number' => 'Meja O02', 'capacity' => 4, 'zone' => 'Outdoor Terrace', 'status' => 'occupied', 'current_customer' => 'Komunitas Gowes'],
            ['table_number' => 'Meja O03', 'capacity' => 2, 'zone' => 'Outdoor Terrace', 'status' => 'available'],
            ['table_number' => 'Meja VIP-01', 'capacity' => 8, 'zone' => 'VIP Meeting Room', 'status' => 'available'],
            ['table_number' => 'Bar-01', 'capacity' => 1, 'zone' => 'Slow Bar Counter', 'status' => 'available'],
        ];

        foreach ($tables as $t) {
            CafeTable::updateOrCreate(
                ['table_number' => $t['table_number']],
                array_merge($t, ['outlet_id' => $outlet1?->id])
            );
        }

        // 3. SEED ADVANCED MEMBERSHIP TIER RULES
        $tierRules = [
            [
                'tier_name' => 'Bronze',
                'min_spending' => 0,
                'min_orders' => 0,
                'point_multiplier' => 1.0,
                'discount_percent' => 0,
                'free_birthday_drink' => false,
                'priority_table' => false,
                'free_upsize' => false,
                'validity_months' => 12,
                'description' => 'Tier selamat datang untuk semua pelanggan baru. Dapatkan 1 poin setiap kelipatan Rp 10.000 belanja.',
            ],
            [
                'tier_name' => 'Silver',
                'min_spending' => 500000,
                'min_orders' => 5,
                'point_multiplier' => 1.25,
                'discount_percent' => 5,
                'free_birthday_drink' => true,
                'priority_table' => false,
                'free_upsize' => false,
                'validity_months' => 12,
                'description' => 'Diskon 5% setiap transaksi, poin 1.25x lebih cepat, serta voucher 1 minuman gratis di hari ulang tahun.',
            ],
            [
                'tier_name' => 'Gold',
                'min_spending' => 2000000,
                'min_orders' => 15,
                'point_multiplier' => 1.5,
                'discount_percent' => 10,
                'free_birthday_drink' => true,
                'priority_table' => true,
                'free_upsize' => false,
                'validity_months' => 12,
                'description' => 'Diskon 10%, poin 1.5x, prioritas reservasi meja VIP, dan promo menu rahasia (secret menu).',
            ],
            [
                'tier_name' => 'Platinum',
                'min_spending' => 5000000,
                'min_orders' => 30,
                'point_multiplier' => 2.0,
                'discount_percent' => 15,
                'free_birthday_drink' => true,
                'priority_table' => true,
                'free_upsize' => true,
                'validity_months' => 12,
                'description' => 'Tier tertinggi! Diskon 15%, poin 2x lipat, gratis upsize cup, prioritas meja, dan undangan event cupping kopi tahunan.',
            ],
        ];

        foreach ($tierRules as $tr) {
            MembershipTierRule::updateOrCreate(['tier_name' => $tr['tier_name']], $tr);
        }
    }
}

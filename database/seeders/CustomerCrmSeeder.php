<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\CustomerPointLog;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;

class CustomerCrmSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori & Menu Contoh jika belum ada
        $coffeeCat = Category::firstOrCreate(['name' => 'Coffee'], ['name' => 'Coffee']);
        $nonCoffeeCat = Category::firstOrCreate(['name' => 'Non-Coffee'], ['name' => 'Non-Coffee']);
        $pastryCat = Category::firstOrCreate(['name' => 'Pastry & Food'], ['name' => 'Pastry & Food']);

        $caramelLatte = Menu::firstOrCreate(
            ['name' => 'Caramel Macchiato'],
            [
                'category_id' => $coffeeCat->id,
                'price' => 38000,
                'large_price' => 5000,
                'stock' => 50,
                'description' => 'Espresso dengan susu segar dan saus karamel lezat.',
                'preparation_time' => 5,
            ]
        );

        $americano = Menu::firstOrCreate(
            ['name' => 'Iced Americano'],
            [
                'category_id' => $coffeeCat->id,
                'price' => 28000,
                'large_price' => 4000,
                'stock' => 100,
                'description' => 'Espresso ganda dingin segar klasik.',
                'preparation_time' => 3,
            ]
        );

        $matcha = Menu::firstOrCreate(
            ['name' => 'Matcha Green Tea Latte'],
            [
                'category_id' => $nonCoffeeCat->id,
                'price' => 35000,
                'large_price' => 5000,
                'stock' => 40,
                'description' => 'Matcha murni Jepang dengan susu segar pilihan.',
                'preparation_time' => 4,
            ]
        );

        $croissant = Menu::firstOrCreate(
            ['name' => 'Butter Croissant'],
            [
                'category_id' => $pastryCat->id,
                'price' => 25000,
                'large_price' => 0,
                'stock' => 30,
                'description' => 'Croissant renyah berlapis dengan butter Prancis.',
                'preparation_time' => 2,
            ]
        );

        $today = Carbon::today();

        // 2. Data Pelanggan CRM
        $customersData = [
            [
                'name' => 'Jessica Angeline (Ultah Hari Ini)',
                'phone' => '081234567891',
                'email' => 'jessica@gmail.com',
                'birth_date' => Carbon::create(1998, $today->month, $today->day)->toDateString(),
                'tier' => 'Platinum',
                'points' => 350,
                'visit_count' => 32,
                'total_spending' => 3450000,
                'address' => 'Jl. Boulevard Hijau No. 12, Jakarta',
                'notes' => 'Suka Caramel Macchiato extra caramel. Pelanggan VIP sejak 2024.',
                'last_visit' => now()->subDays(2),
            ],
            [
                'name' => 'Budi Santoso (Ultah Minggu Ini)',
                'phone' => '081298765432',
                'email' => 'budi.santoso@yahoo.com',
                'birth_date' => $today->copy()->addDays(3)->setYear(1995)->toDateString(),
                'tier' => 'Gold',
                'points' => 180,
                'visit_count' => 18,
                'total_spending' => 1850000,
                'address' => 'Jl. Tebet Barat Raya No. 45, Jakarta Selatan',
                'notes' => 'Sering meeting di kafe hari kerja siang.',
                'last_visit' => now()->subDays(5),
            ],
            [
                'name' => 'Amanda Putri',
                'phone' => '085712345678',
                'email' => 'amanda.putri@gmail.com',
                'birth_date' => Carbon::create(2001, $today->month, 28)->toDateString(),
                'tier' => 'Silver',
                'points' => 75,
                'visit_count' => 8,
                'total_spending' => 720000,
                'address' => 'Apartemen Sudirman Tower 1, Lt 15',
                'notes' => 'Paling suka Matcha Latte oat milk.',
                'last_visit' => now()->subDay(),
            ],
            [
                'name' => 'Rizky Pratama',
                'phone' => '087812349999',
                'email' => 'rizky.pratama@outlook.com',
                'birth_date' => Carbon::create(1997, 11, 15)->toDateString(),
                'tier' => 'Bronze',
                'points' => 20,
                'visit_count' => 2,
                'total_spending' => 190000,
                'address' => 'Jl. Menteng Raya No. 8',
                'notes' => 'Pelanggan baru area perkantoran.',
                'last_visit' => now()->subWeeks(1),
            ],
        ];

        foreach ($customersData as $data) {
            $customer = Customer::updateOrCreate(
                ['phone' => $data['phone']],
                $data
            );

            // Log Poin
            CustomerPointLog::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'description' => 'Akumulasi Poin Reward Belanja',
                ],
                [
                    'customer_id' => $customer->id,
                    'points' => $customer->points,
                    'type' => 'earned',
                    'description' => 'Akumulasi Poin Reward Belanja',
                ]
            );

            // Buat Riwayat Pesanan Contoh
            $existingOrder = Order::where('phone', $customer->phone)->first();
            if (!$existingOrder) {
                $order1 = Order::create([
                    'order_number' => 'ORD' . date('Ymd') . rand(1000, 9999),
                    'customer_name' => $customer->name,
                    'phone' => $customer->phone,
                    'table_number' => 'A02',
                    'visit_type' => 'Dine In',
                    'payment' => 'QRIS',
                    'note' => 'Less ice',
                    'subtotal' => 63000,
                    'tax' => 6930,
                    'service' => 3000,
                    'total' => 72930,
                    'status' => 'Completed',
                    'created_at' => now()->subDays(2),
                ]);

                OrderDetail::create([
                    'order_id' => $order1->id,
                    'menu_id' => $caramelLatte->id,
                    'qty' => 1,
                    'size' => 'Large',
                    'price' => 38000,
                    'total' => 38000,
                ]);

                OrderDetail::create([
                    'order_id' => $order1->id,
                    'menu_id' => $croissant->id,
                    'qty' => 1,
                    'price' => 25000,
                    'total' => 25000,
                ]);

                $order2 = Order::create([
                    'order_number' => 'ORD' . date('Ymd') . rand(1000, 9999),
                    'customer_name' => $customer->name,
                    'phone' => $customer->phone,
                    'table_number' => 'A05',
                    'visit_type' => 'Dine In',
                    'payment' => 'Cash',
                    'subtotal' => 66000,
                    'tax' => 7260,
                    'service' => 3000,
                    'total' => 76260,
                    'status' => 'Completed',
                    'created_at' => now()->subDays(10),
                ]);

                OrderDetail::create([
                    'order_id' => $order2->id,
                    'menu_id' => $americano->id,
                    'qty' => 1,
                    'price' => 28000,
                    'total' => 28000,
                ]);

                OrderDetail::create([
                    'order_id' => $order2->id,
                    'menu_id' => $caramelLatte->id,
                    'qty' => 1,
                    'price' => 38000,
                    'total' => 38000,
                ]);
            }
        }
    }
}

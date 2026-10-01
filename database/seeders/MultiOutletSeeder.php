<?php

namespace Database\Seeders;

use App\Models\CentralWarehouseStock;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class MultiOutletSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED OUTLETS
        $outlet1 = Outlet::updateOrCreate(
            ['code' => 'OUT-001'],
            [
                'name' => 'Kopi Kita - Sudirman Hub (Pusat)',
                'address' => 'Jl. Jenderal Sudirman Kav. 21, Karet Semanggi',
                'city' => 'Jakarta Selatan',
                'phone' => '021-5234889',
                'email' => 'sudirman@kopikita.id',
                'manager_name' => 'Budi Santoso',
                'status' => 'active',
                'is_central_warehouse' => true,
                'operating_hours' => '07:00 - 22:00',
            ]
        );

        $outlet2 = Outlet::updateOrCreate(
            ['code' => 'OUT-002'],
            [
                'name' => 'Kopi Kita - Senopati',
                'address' => 'Jl. Senopati Raya No. 42, Kebayoran Baru',
                'city' => 'Jakarta Selatan',
                'phone' => '021-7299102',
                'email' => 'senopati@kopikita.id',
                'manager_name' => 'Rian Pratama',
                'status' => 'active',
                'is_central_warehouse' => false,
                'operating_hours' => '08:00 - 23:00',
            ]
        );

        $outlet3 = Outlet::updateOrCreate(
            ['code' => 'OUT-003'],
            [
                'name' => 'Kopi Kita - Dago Heritage',
                'address' => 'Jl. Ir. H. Juanda No. 118, Dago',
                'city' => 'Bandung',
                'phone' => '022-4219088',
                'email' => 'dago@kopikita.id',
                'manager_name' => 'Siti Rahmawati',
                'status' => 'active',
                'is_central_warehouse' => false,
                'operating_hours' => '08:00 - 22:00',
            ]
        );

        // 2. ASSIGN EXISTING ORDERS TO OUTLETS
        $outlets = [$outlet1->id, $outlet2->id, $outlet3->id];
        $orders = Order::whereNull('outlet_id')->get();
        foreach ($orders as $idx => $order) {
            $assignedOutletId = $outlets[$idx % count($outlets)];
            $order->update(['outlet_id' => $assignedOutletId]);
        }

        // 3. SEED CENTRAL WAREHOUSE STOCKS
        $warehouseItems = [
            [
                'item_name' => 'Biji Kopi Arabika House Blend',
                'sku' => 'WH-COF-001',
                'category' => 'Bahan Baku Kopi',
                'stock_quantity' => 185.50,
                'unit' => 'kg',
                'min_stock' => 50.00,
                'unit_cost' => 140000,
                'supplier' => 'PT Roastery Nusantara',
                'notes' => 'Blend 70% Gayo Arabika, 30% Mandheling',
            ],
            [
                'item_name' => 'Biji Kopi Robusta Fine',
                'sku' => 'WH-COF-002',
                'category' => 'Bahan Baku Kopi',
                'stock_quantity' => 90.00,
                'unit' => 'kg',
                'min_stock' => 30.00,
                'unit_cost' => 95000,
                'supplier' => 'PT Roastery Nusantara',
                'notes' => 'Robusta Dampit Grade 1',
            ],
            [
                'item_name' => 'Susu UHT Full Cream (1L)',
                'sku' => 'WH-DAI-001',
                'category' => 'Dairy & Susu',
                'stock_quantity' => 340.00,
                'unit' => 'kotak',
                'min_stock' => 100.00,
                'unit_cost' => 18500,
                'supplier' => 'CV Dairy Fresh Sejahtera',
                'notes' => 'Masa simpan 6 bulan',
            ],
            [
                'item_name' => 'Oat Milk Barista Edition (1L)',
                'sku' => 'WH-DAI-002',
                'category' => 'Dairy & Susu',
                'stock_quantity' => 110.00,
                'unit' => 'kotak',
                'min_stock' => 40.00,
                'unit_cost' => 38000,
                'supplier' => 'CV Dairy Fresh Sejahtera',
                'notes' => 'Untuk menu plant-based',
            ],
            [
                'item_name' => 'Sirup Salted Caramel 750ml',
                'sku' => 'WH-SYR-001',
                'category' => 'Sirup & Perisa',
                'stock_quantity' => 45.00,
                'unit' => 'botol',
                'min_stock' => 20.00,
                'unit_cost' => 125000,
                'supplier' => 'PT Flavor Prima',
                'notes' => 'Premium flavour syrup',
            ],
            [
                'item_name' => 'Pure Matcha Powder Grade A',
                'sku' => 'WH-POW-001',
                'category' => 'Bubuk Minuman',
                'stock_quantity' => 18.00,
                'unit' => 'kg',
                'min_stock' => 10.00,
                'unit_cost' => 290000,
                'supplier' => 'PT Tea Import Jaya',
                'notes' => 'Uji Kyoto import matcha',
            ],
            [
                'item_name' => 'Paper Cup Hot 8oz + Lid',
                'sku' => 'WH-PKG-001',
                'category' => 'Kemasan / Packaging',
                'stock_quantity' => 1500.00,
                'unit' => 'pcs',
                'min_stock' => 500.00,
                'unit_cost' => 950,
                'supplier' => 'CV Eco Packaging',
                'notes' => 'Paper cup biodegradable',
            ],
            [
                'item_name' => 'Plastic Cup Cold 16oz + Straw',
                'sku' => 'WH-PKG-002',
                'category' => 'Kemasan / Packaging',
                'stock_quantity' => 2400.00,
                'unit' => 'pcs',
                'min_stock' => 800.00,
                'unit_cost' => 1200,
                'supplier' => 'CV Eco Packaging',
                'notes' => 'Cup sablon logo Kopi Kita',
            ],
        ];

        foreach ($warehouseItems as $wItem) {
            CentralWarehouseStock::updateOrCreate(['sku' => $wItem['sku']], $wItem);
        }

        // 4. SEED SAMPLE STOCK TRANSFERS
        $adminUser = User::first();
        $whCoffee = CentralWarehouseStock::where('sku', 'WH-COF-001')->first();
        $whMilk = CentralWarehouseStock::where('sku', 'WH-DAI-001')->first();
        $whCup = CentralWarehouseStock::where('sku', 'WH-PKG-002')->first();

        // Transfer 1: Completed (Gudang Pusat -> Senopati)
        $trf1 = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-20260928-001'],
            [
                'from_outlet_id' => $outlet1->id,
                'to_outlet_id' => $outlet2->id,
                'requested_by' => $adminUser?->id,
                'approved_by' => $adminUser?->id,
                'status' => 'Completed',
                'transfer_date' => now()->subDays(3)->toDateString(),
                'notes' => 'Restok mingguan biji kopi & susu untuk Outlet Senopati',
            ]
        );

        StockTransferItem::firstOrCreate(
            ['stock_transfer_id' => $trf1->id, 'item_name' => 'Biji Kopi Arabika House Blend'],
            [
                'warehouse_stock_id' => $whCoffee?->id,
                'quantity' => 25.00,
                'unit' => 'kg',
                'notes' => 'Kondisi kemasan rapi kedap udara',
            ]
        );

        StockTransferItem::firstOrCreate(
            ['stock_transfer_id' => $trf1->id, 'item_name' => 'Susu UHT Full Cream (1L)'],
            [
                'warehouse_stock_id' => $whMilk?->id,
                'quantity' => 60.00,
                'unit' => 'kotak',
                'notes' => 'Batch exp: 2027-02-15',
            ]
        );

        // Transfer 2: In Transit (Gudang Pusat -> Dago)
        $trf2 = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-20260930-002'],
            [
                'from_outlet_id' => $outlet1->id,
                'to_outlet_id' => $outlet3->id,
                'requested_by' => $adminUser?->id,
                'approved_by' => $adminUser?->id,
                'status' => 'In Transit',
                'transfer_date' => now()->subDay()->toDateString(),
                'notes' => 'Pengiriman kargo Bandung via Ekspedisi Baraka',
            ]
        );

        StockTransferItem::firstOrCreate(
            ['stock_transfer_id' => $trf2->id, 'item_name' => 'Plastic Cup Cold 16oz + Straw'],
            [
                'warehouse_stock_id' => $whCup?->id,
                'quantity' => 500.00,
                'unit' => 'pcs',
                'notes' => '1 dus isi 500 pcs',
            ]
        );

        StockTransferItem::firstOrCreate(
            ['stock_transfer_id' => $trf2->id, 'item_name' => 'Biji Kopi Arabika House Blend'],
            [
                'warehouse_stock_id' => $whCoffee?->id,
                'quantity' => 30.00,
                'unit' => 'kg',
                'notes' => 'Pengiriman ke Dago',
            ]
        );

        // Transfer 3: Pending (Senopati -> Dago)
        $trf3 = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-20261001-003'],
            [
                'from_outlet_id' => $outlet2->id,
                'to_outlet_id' => $outlet3->id,
                'requested_by' => $adminUser?->id,
                'approved_by' => null,
                'status' => 'Pending',
                'transfer_date' => now()->toDateString(),
                'notes' => 'Permintaan bantuan darurat sirup caramel',
            ]
        );

        StockTransferItem::firstOrCreate(
            ['stock_transfer_id' => $trf3->id, 'item_name' => 'Sirup Salted Caramel 750ml'],
            [
                'warehouse_stock_id' => null,
                'quantity' => 6.00,
                'unit' => 'botol',
                'notes' => 'Transfer darurat antar cabang',
            ]
        );

        // 5. UPDATE EXISTING USERS WITH OUTLET ASSIGNMENT
        if ($adminUser) {
            // Owner / Superadmin has access to all or central hub
            $adminUser->update(['outlet_id' => null]);
        }
    }
}

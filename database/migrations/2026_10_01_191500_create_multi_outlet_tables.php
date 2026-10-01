<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. OUTLETS TABLE
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('manager_name')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_central_warehouse')->default(false);
            $table->string('operating_hours')->default('08:00 - 22:00');
            $table->timestamps();
        });

        // 2. Add outlet_id to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'outlet_id')) {
                $table->foreignId('outlet_id')->nullable()->after('id')->constrained('outlets')->nullOnDelete();
            }
        });

        // 3. Add outlet_id to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'outlet_id')) {
                $table->foreignId('outlet_id')->nullable()->after('role')->constrained('outlets')->nullOnDelete();
            }
        });

        // 4. CENTRAL WAREHOUSE STOCKS
        Schema::create('central_warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('sku')->unique();
            $table->string('category')->default('Bahan Baku');
            $table->decimal('stock_quantity', 12, 2)->default(0);
            $table->string('unit', 30)->default('kg');
            $table->decimal('min_stock', 12, 2)->default(10);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 5. STOCK TRANSFERS
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique();
            $table->foreignId('from_outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
            $table->foreignId('to_outlet_id')->constrained('outlets')->cascadeOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['Pending', 'In Transit', 'Completed', 'Cancelled'])->default('Pending');
            $table->date('transfer_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 6. STOCK TRANSFER ITEMS
        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_transfer_id')->constrained('stock_transfers')->cascadeOnDelete();
            $table->foreignId('warehouse_stock_id')->nullable()->constrained('central_warehouse_stocks')->nullOnDelete();
            $table->string('item_name');
            $table->decimal('quantity', 12, 2);
            $table->string('unit', 30);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_items');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('central_warehouse_stocks');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'outlet_id')) {
                $table->dropForeign(['outlet_id']);
                $table->dropColumn('outlet_id');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'outlet_id')) {
                $table->dropForeign(['outlet_id']);
                $table->dropColumn('outlet_id');
            }
        });

        Schema::dropIfExists('outlets');
    }
};

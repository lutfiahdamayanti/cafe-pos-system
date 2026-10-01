<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ADD RECIPE FIELDS TO MENUS
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'recipe_steps')) {
                $table->text('recipe_steps')->nullable()->after('ingredients');
            }
            if (!Schema::hasColumn('menus', 'serving_temp')) {
                $table->string('serving_temp')->nullable()->after('recipe_steps'); // Hot (85°C), Iced (4°C), Room Temp
            }
            if (!Schema::hasColumn('menus', 'taste_notes')) {
                $table->string('taste_notes')->nullable()->after('serving_temp'); // Bold, Sweet, Fruity, Balanced
            }
        });

        // 2. CAFE TABLES (QR Table Ordering)
        Schema::create('cafe_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
            $table->string('table_number')->unique();
            $table->integer('capacity')->default(4);
            $table->string('zone')->default('Indoor'); // Indoor, Outdoor, VIP, Bar
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
            $table->string('current_customer')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        // 3. ADVANCED MEMBERSHIP TIER RULES
        Schema::create('membership_tier_rules', function (Blueprint $table) {
            $table->id();
            $table->string('tier_name')->unique(); // Bronze, Silver, Gold, Platinum
            $table->decimal('min_spending', 12, 2)->default(0);
            $table->integer('min_orders')->default(0);
            $table->decimal('point_multiplier', 4, 2)->default(1.0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->boolean('free_birthday_drink')->default(false);
            $table->boolean('priority_table')->default(false);
            $table->boolean('free_upsize')->default(false);
            $table->integer('validity_months')->default(12);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_tier_rules');
        Schema::dropIfExists('cafe_tables');

        Schema::table('menus', function (Blueprint $table) {
            if (Schema::hasColumn('menus', 'taste_notes')) {
                $table->dropColumn('taste_notes');
            }
            if (Schema::hasColumn('menus', 'serving_temp')) {
                $table->dropColumn('serving_temp');
            }
            if (Schema::hasColumn('menus', 'recipe_steps')) {
                $table->dropColumn('recipe_steps');
            }
        });
    }
};

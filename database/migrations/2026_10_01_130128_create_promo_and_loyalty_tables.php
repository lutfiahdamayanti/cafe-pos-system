<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Promo & Loyalty.
     */
    public function up(): void
    {
        // 1. Voucher & Kupon Promo (Fitur 1 & 2: Persen/Nominal, Minimal Belanja & Masa Aktif)
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 12, 2);
            $table->decimal('max_discount', 12, 2)->nullable();
            $table->decimal('min_spending', 12, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->decimal('discount_amount', 12, 2);
            $table->timestamps();
        });

        // 2. Point Reward (Fitur 3: Katalog Penukaran Poin)
        Schema::create('point_rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('points_required');
            $table->enum('reward_type', ['product', 'discount', 'merchandise'])->default('product');
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('point_reward_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('point_reward_id')->constrained('point_rewards')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->integer('points_spent');
            $table->string('claim_code')->unique();
            $table->enum('status', ['claimed', 'used', 'cancelled'])->default('claimed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Stamp Card (Fitur 4: Kartu Stamp Digital)
        Schema::create('stamp_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('target_stamps')->default(10);
            $table->decimal('min_purchase', 12, 2)->default(0);
            $table->string('reward_description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customer_stamps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('stamp_program_id')->constrained('stamp_programs')->cascadeOnDelete();
            $table->integer('current_stamps')->default(0);
            $table->integer('total_completed_cards')->default(0);
            $table->timestamps();
        });

        Schema::create('stamp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('stamp_program_id')->constrained('stamp_programs')->cascadeOnDelete();
            $table->integer('stamps');
            $table->enum('action', ['add', 'redeem'])->default('add');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 4. Cashback (Fitur 5: Skema & Program Cashback)
        Schema::create('cashback_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 12, 2);
            $table->enum('reward_as', ['points', 'balance_discount'])->default('points');
            $table->decimal('min_spending', 12, 2)->default(0);
            $table->decimal('max_cashback', 12, 2)->nullable();
            $table->string('tier_eligibility')->default('All');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cashback_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashback_rule_id')->nullable()->constrained('cashback_rules')->nullOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->integer('points_rewarded')->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        // 5. Referral Program (Fitur 6: Member-Get-Member Referral)
        Schema::table('customers', function (Blueprint $table) {
            $table->string('referral_code')->nullable()->unique()->after('points');
            $table->foreignId('referred_by_id')->nullable()->after('referral_code')->constrained('customers')->nullOnDelete();
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('referee_id')->constrained('customers')->cascadeOnDelete();
            $table->integer('referrer_points_rewarded')->default(0);
            $table->integer('referee_points_rewarded')->default(0);
            $table->enum('status', ['registered', 'transacted', 'rewarded'])->default('registered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['referred_by_id']);
            $table->dropColumn(['referral_code', 'referred_by_id']);
        });

        Schema::dropIfExists('cashback_logs');
        Schema::dropIfExists('cashback_rules');
        Schema::dropIfExists('stamp_logs');
        Schema::dropIfExists('customer_stamps');
        Schema::dropIfExists('stamp_programs');
        Schema::dropIfExists('point_reward_claims');
        Schema::dropIfExists('point_rewards');
        Schema::dropIfExists('voucher_usages');
        Schema::dropIfExists('vouchers');
    }
};

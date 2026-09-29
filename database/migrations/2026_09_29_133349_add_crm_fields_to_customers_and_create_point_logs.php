<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('email');
            $table->enum('tier', ['Bronze', 'Silver', 'Gold', 'Platinum'])->default('Bronze')->after('total_spending');
            $table->integer('points')->default(0)->after('tier');
            $table->text('address')->nullable()->after('points');
            $table->text('notes')->nullable()->after('address');
        });

        Schema::create('customer_point_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->integer('points'); // can be positive or negative
            $table->enum('type', ['earned', 'redeemed', 'adjusted'])->default('earned');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_point_logs');

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'tier', 'points', 'address', 'notes']);
        });
    }
};

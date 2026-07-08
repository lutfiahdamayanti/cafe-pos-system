<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->string('cancel_reason')->nullable();

            $table->string('refund_reason')->nullable();

            $table->decimal('refund_amount',10,2)->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropColumn([
                'cancel_reason',
                'refund_reason',
                'refund_amount'
            ]);

        });
    }
};
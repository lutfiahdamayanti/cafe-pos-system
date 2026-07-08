<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            $table->string('order_number')->unique();

            $table->string('customer_name');

            $table->string('phone');

            $table->string('table_number')->nullable();

            $table->enum('visit_type',[
                'Dine In',
                'Take Away'
            ]);

            $table->string('payment');

            $table->text('note')->nullable();

            $table->double('subtotal');

            $table->double('tax');

            $table->double('service');

            $table->double('total');

            $table->enum('status',[
                'Pending',
                'Accepted',
                'Processing',
                'Ready',
                'Completed',
                'Cancelled'
            ])->default('Pending');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
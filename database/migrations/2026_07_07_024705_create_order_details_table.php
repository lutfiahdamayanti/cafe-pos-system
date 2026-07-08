<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('order_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->foreignId('menu_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->integer('qty');

            $table->string('size')->nullable();

            $table->double('price');

            $table->double('total');

            $table->text('note')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
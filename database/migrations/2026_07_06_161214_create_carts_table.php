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
    Schema::create('carts', function (Blueprint $table) {
        $table->id();

        $table->foreignId('menu_id')->constrained()->onDelete('cascade');

        $table->integer('qty')->default(1);

        $table->string('size')->default('Regular');

        $table->string('sugar_level')->nullable();

        $table->string('ice_level')->nullable();

        $table->text('note')->nullable();

        $table->integer('price');

        $table->integer('total');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};

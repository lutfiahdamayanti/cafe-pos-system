<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {

            $table->id();

            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Informasi Menu
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();

            // Informasi Tambahan
            $table->float('rating')->default(5);
            $table->integer('preparation_time')->default(10);
            $table->integer('calories')->nullable();
            $table->string('allergen')->nullable();

            // Stok
            $table->integer('stock')->default(0);

            // Badge
            $table->boolean('promo')->default(false);
            $table->boolean('best_seller')->default(false);
            $table->boolean('is_new')->default(false);

            // Status
            $table->boolean('is_available')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
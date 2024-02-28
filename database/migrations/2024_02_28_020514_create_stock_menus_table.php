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
        Schema::create('stock_menus', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->uuid('menu_id')->nullable(false)->unique();
            $table->decimal('min_stock', 10, 2)->nullable(true)->default(0.00);
            $table->decimal('in_stock', 10, 2)->nullable(false)->default(0.00);

            $table->timestamps();

            $table->foreign('menu_id')->on('restaurant_menus')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_menus');
    }
};

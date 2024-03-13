<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->uuid('items_id')->nullable(false);
            $table->decimal('incoming_qty', 15, 2)->nullable(true)->default(0.00);
            $table->decimal('incoming_value', 15, 2)->nullable(true)->default(0.00);
            $table->decimal('price_diff', 15, 2)->nullable(true)->default(0.00);
            $table->decimal('inventory_value', 15, 2)->nullable(true)->default(0.00);
            $table->decimal('qty_on_hand', 15, 2)->nullable(false)->default(0);
            $table->decimal('avg_cost', 15, 2)->default(0.00);
            $table->decimal('last_cost', 15, 2)->default(0.00);
            $table->decimal('minimum_stock', 15, 2)->nullable(true)->default(0.00);
            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};

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
        Schema::create('warehouse_shipping_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_shippings_id')->nullable(false);
            $table->uuid('stock_items_id')->nullable(false);
            $table->uuid('created_by')->nullable(true);

            $table->foreign('warehouse_shippings_id')->on('warehouse_shippings')->references('id')->onDelete('cascade');
            $table->foreign('stock_items_id')->on('stock_items')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_shipping_items');
    }
};

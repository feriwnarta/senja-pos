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
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            $table->dropForeign('warehouse_shippings_stock_items_id_foreign');
            $table->foreign('stock_items_id')->references('id')->on('stock_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            $table->dropForeign('warehouse_shippings_stock_items_id_foreign');
            $table->foreign('stock_items_id')->references('id')->on('stock_items');
        });
    }
};

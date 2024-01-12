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
            $table->dropColumn('stock_items_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            // Allow null values temporarily for flexibility:
            $table->uuid('stock_items_id')->nullable()->after('id');  // Consider placement

            // Add the foreign key constraint after data validation:
            $table->foreign('stock_items_id')
                ->on('stock_items')
                ->references('id')
                ->onDelete('cascade');
        });
    }
};

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
        Schema::table('stock_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('items_id');
            $table->uuid('warehouse_items_id')->nullable(false)->after('id');
            $table->foreign('warehouse_items_id')->on('warehouse_items')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warehouse_items_id');
            $table->uuid('items_id')->nullable(false);
            $table->foreign('items_id')->on('items')->references('id')->onDelete('cascade');
        });
    }
};

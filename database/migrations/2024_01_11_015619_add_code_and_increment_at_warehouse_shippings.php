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
            $table->string('code')->nullable(false)->after('stock_items_id')->unique();
            $table->bigInteger('increment')->nullable(false)->after('stock_items_id');
            $table->uuid('warehouses_id')->nullable(false)->after('warehouse_outbounds_id');
            $table->foreign('warehouses_id')->on('warehouses')->references('id')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('increment');
            $table->dropConstrainedForeignId('warehouses_id');
        });
    }
};

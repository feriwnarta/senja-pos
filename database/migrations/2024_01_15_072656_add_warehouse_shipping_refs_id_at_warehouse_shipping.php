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
            $table->uuid('warehouse_shipping_refs_id')->nullable(false)->after('id');
            $table->foreign('warehouse_shipping_refs_id')->on('warehouse_shipping_refs')->references('id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warehouse_shippings');
        });
    }
};

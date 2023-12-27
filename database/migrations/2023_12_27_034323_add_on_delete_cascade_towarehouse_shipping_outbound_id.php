<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            $table->dropForeign('warehouse_shippings_warehouse_outbounds_id_foreign');
            $table->foreign('warehouse_outbounds_id')->references('id')->on('warehouse_outbounds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            $table->dropForeign('warehouse_shippings_warehouse_outbounds_id_foreign');
            $table->foreign('warehouse_outbounds_id')->references('id')->on('warehouse_outbounds');
        });
    }
};

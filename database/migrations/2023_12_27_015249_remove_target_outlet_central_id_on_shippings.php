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
            $table->dropForeign('warehouse_shippings_target_outlet_id_foreign');
            $table->dropForeign('warehouse_shippings_target_central_id_foreign');

            $table->dropColumn('target_central_id');
            $table->dropColumn('target_outlet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            $table->uuid('target_central_id')->nullable(false);
            $table->uuid('target_outlet_id')->nullable(false);

            $table->foreign('target_central_id')->references('id')->on('central_kitchens');
            $table->foreign('target_outlet_id')->references('id')->on('outlets');
        });
    }
};

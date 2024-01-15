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
            $table->dropConstrainedForeignId('warehouse_outbounds_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_shippings', function (Blueprint $table) {
            $table->uuid('warehouse_outbounds_id')->nullable(false);
            $table->foreign('warehouse_outbounds_id')->references('id')->on('warehouse_outbounds');
        });
    }
};

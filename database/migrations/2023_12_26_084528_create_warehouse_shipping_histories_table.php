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
        Schema::create('warehouse_shipping_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_shippings_id')->nullable(false);
            $table->enum('status', ['Dikirim', 'Terkirim', 'Pengiriman Gagal']);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();

            $table->foreign('warehouse_shippings_id')->references('id')->on('warehouse_shippings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_shipping_histories');
    }
};

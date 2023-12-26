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
        Schema::create('warehouse_shippings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_outbounds_id')->nullable(false);
            $table->uuid('target_outlet_id')->nullable(true);
            $table->uuid('target_central_id')->nullable(true);
            $table->text('description')->nullable(true);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();

            $table->foreign('warehouse_outbounds_id')->references('id')->on('warehouse_outbounds');
            $table->foreign('target_outlet_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->foreign('target_central_id')->references('id')->on('central_kitchens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_shippings');
    }
};

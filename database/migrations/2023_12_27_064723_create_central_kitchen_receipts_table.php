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
        Schema::create('central_kitchen_receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_id')->nullable(false);
            $table->uuid('warehouse_outbounds_id')->nullable(false);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('central_productions_id')->references('id')->on('central_productions');
            $table->foreign('warehouse_outbounds_id')->references('id')->on('warehouse_outbounds');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_kitchen_receipts');
    }
};

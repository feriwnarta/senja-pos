<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('central_production_addition_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_id')->nullable(false);
            $table->uuid('warehouse_outbounds_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('amount_addition', 15, 2);
            $table->decimal('amount_received', 15, 2);

            $table->foreign('central_productions_id', 'cparfk')->references('id')->on('central_productions');
            $table->foreign('warehouse_outbounds_id', 'cparwofk')->references('id')->on('warehouse_outbounds');
            $table->foreign('items_id')->references('id')->on('items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_addition_requests');
    }
};

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
        Schema::create('central_production_component_costs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('qty_on_hand', 15, 2)->nullable(false);
            $table->decimal('avg_cost', 15, 2)->default(0.00);
            $table->decimal('last_cost', 15, 2)->default(0.00);
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('central_productions_id', 'cpidfk')->references('id')->on('central_productions');
            $table->foreign('items_id')->references('id')->on('items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_components');
    }
};

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
        Schema::create('central_production_finish_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_production_finishes_id')->nullable(false);
            $table->uuid('item_id')->nullable(false);
            $table->decimal('amount_received', 15,2);
            $table->decimal('amount_used', 15,2);
            $table->decimal('avg_cost', 15, 2);
            $table->decimal('last_cost', 15, 2);
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_finish_details');
    }
};

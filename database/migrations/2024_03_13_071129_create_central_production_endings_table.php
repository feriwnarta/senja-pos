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
        Schema::create('central_production_endings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_id');
            $table->uuid('target_items_id');
            $table->decimal('qty', 15, 2);

            $table->foreign('central_productions_id')->references('id')->on('central_productions');
            $table->foreign('target_items_id')->references('id')->on('items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_endings');
    }
};

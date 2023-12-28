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
        Schema::create('central_production_remaining_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_remaining_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('qty_remaining', 15, 2)->nullable(false)->default(0.00);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('central_productions_remaining_id', 'fk_cp_remaining')->references('id')->on('central_production_remainings')->onDelete('cascade');
            $table->foreign('items_id', 'fk_items')->references('id')->on('items')->onDelete('cascade');

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_remaining_details');
    }
};

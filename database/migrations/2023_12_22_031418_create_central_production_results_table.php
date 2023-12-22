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
        Schema::create('central_production_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_id')->nullable(false);
            $table->uuid('target_items_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('qty_target', 10, 2)->nullable(false);
            $table->decimal('qty_result', 10, 2)->default(0.00);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('central_productions_id')->references('id')->on('central_productions')->onDelete('cascade');
            $table->foreign('target_items_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_results');
    }
};

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
        Schema::create('recipe_item_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('recipe_items_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->uuid('units_id')->nullable(false);
            $table->decimal('usage', 10, 2)->nullable(false);
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('recipe_items_id')->references('id')->on('recipe_items')->onDelete('cascade');
            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('units_id')->references('id')->on('units')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_item_details');
    }
};

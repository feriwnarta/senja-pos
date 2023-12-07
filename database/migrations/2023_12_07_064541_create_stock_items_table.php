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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->uuid('items_id')->nullable(false);
            $table->bigInteger('minimum_qty')->nullable(true)->default(0);
            $table->bigInteger('qty')->nullable(false)->default(0);
            $table->decimal('init_avg_cost', 15, 2)->default(0.00);
            $table->decimal('init_last_cost', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};

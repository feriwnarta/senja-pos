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
            $table->bigInteger('minimum_stock')->nullable(true)->default(0);
            $table->bigInteger('stock')->nullable(false)->default(0);
            $table->decimal('init_avg_cost', 15, 2)->default(0.00);
            $table->decimal('init_last_cost', 15, 2)->default(0.00);
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
        Schema::dropIfExists('stock_items');
    }
};

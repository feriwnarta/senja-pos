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
        Schema::create('central_kitchen_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_kitchens_id')->nullable(false);
            $table->uuid('items_id')->nullable(false)->unique();
            $table->decimal('qty_on_hand', 15, 2);
            $table->decimal('avg_cost', 15, 2);
            $table->decimal('last_cost', 15, 2);

            $table->foreign('central_kitchens_id')->references('id')->on('central_kitchens');
            $table->foreign('items_id')->references('id')->on('items');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_kitchen_stocks');
    }
};

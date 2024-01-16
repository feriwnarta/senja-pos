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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('purchases_id')->nullable(false);
            $table->uuid('suppliers_id')->nullable(true);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('qty_buy', 15, 2)->default(0.00);
            $table->decimal('qty_accept', 15, 2)->default(0.00);
            $table->decimal('single_price', 15, 2)->default(0.00);
            $table->decimal('total_price', 15, 2)->default(0.00);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('purchases_id')->on('purchases')->references('id')->onDelete('cascade');
            $table->foreign('suppliers_id')->on('suppliers')->references('id')->onDelete('cascade');
            $table->foreign('items_id')->on('items')->references('id')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};

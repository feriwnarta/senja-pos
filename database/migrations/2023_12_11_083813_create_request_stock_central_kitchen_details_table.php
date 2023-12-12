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
        Schema::create('request_stock_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_stocks_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('qty', 15, 2)->nullable(false)->default(0.00);
            $table->enum('type', ['PO', 'PROUCE'])->nullable(false);
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);
            $table->foreign('request_stocks_id')->references('id')->on('request_stocks')->onDelete('cascade');
            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_stock_details');
    }
};


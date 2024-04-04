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
        Schema::create('purchase_request_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('purchase_requests_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('qty_buy', 15, 2)->nullable(false);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('purchase_requests_id')->references('id')->on('purchase_requests')->onDelete('cascade');
            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_details');
    }
};

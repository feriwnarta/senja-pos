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
        Schema::create('warehouse_item_receipt_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_item_receipts_id')->nullable(false);
            $table->string('desc')->nullable(true);
            $table->string('status')->nullable(false)->default('DRAFT');
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('warehouse_item_receipts_id', 'wiridFk')->references('id')->on('warehouse_item_receipts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_item_receipt_histories');
    }
};

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
        Schema::create('warehouse_item_receipt_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_items_receipts_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('qty_accept', 10, 2)->nullable(false);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            // Pastikan tipe data kolom sama
            $table->foreign('warehouse_items_receipts_id', 'itemReceiptFK')->references('id')->on('warehouse_item_receipts')->onDelete('cascade');
            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_item_receipt_details');
    }
};

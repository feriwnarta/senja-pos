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
        Schema::table('warehouse_item_receipts', function (Blueprint $table) {
            $table->uuid('warehouse_item_receipt_refs_id')->nullable(false)->after('id');
            $table->foreign('warehouse_item_receipt_refs_id', 'wirridFk')->references('id')->on('warehouse_item_receipt_refs')->onDelete('cascade');
            $table->unique('warehouse_item_receipt_refs_id', 'wirridUnique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_item_receipts', function (Blueprint $table) {
            $table->dropUnique('wirridUnique');
            $table->dropForeign('wirridFk');
            $table->dropColumn('warehouse_item_receipt_refs_id');
        });
    }
};

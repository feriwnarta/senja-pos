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
        Schema::table('warehouse_item_receipt_details', function (Blueprint $table) {
            $table->decimal('qty_send', 15, 2)->after('items_id')->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouse_item_receipt_details', function (Blueprint $table) {
            $table->dropColumn('qty_send');
        });
    }
};

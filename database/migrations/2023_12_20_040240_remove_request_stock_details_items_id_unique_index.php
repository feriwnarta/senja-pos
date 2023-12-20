<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('request_stock_details', function (Blueprint $table) {
            $table->dropForeign('request_stock_details_items_id_foreign');
            $table->dropUnique('request_stock_details_items_id_unique');
            $table->foreign('items_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_stock_details', function (Blueprint $table) {
            $table->foreign('items_id')->references('id')->on('items');
            $table->unique('items_id');
        });
    }
};

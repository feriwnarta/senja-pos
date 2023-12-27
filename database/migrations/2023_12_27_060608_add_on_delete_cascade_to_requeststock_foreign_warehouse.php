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
        Schema::table('request_stocks', function (Blueprint $table) {
            $table->dropForeign('request_stocks_warehouses_id_foreign');
            $table->foreign('warehouses_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_stocks', function (Blueprint $table) {
            $table->dropForeign('request_stocks_warehouses_id_foreign');
            $table->foreign('warehouses_id')->references('id')->on('warehouses');
        });
    }
};

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
        Schema::table('request_stock_details', function (Blueprint $table) {
            $table->decimal('qty_accept', 15, 2)->nullable(false)->default(0.00)->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_stock_details', function (Blueprint $table) {
            $table->dropColumn('qty_accept');
        });
    }
};

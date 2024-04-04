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
        Schema::table('central_production_remaining_details', function (Blueprint $table) {
            $table->decimal('avg_cost', 15, 2)->after('qty_remaining');
            $table->decimal('last_cost', 15, 2)->after('avg_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_production_remaining_details', function (Blueprint $table) {
            $table->dropColumn('avg_cost');
            $table->dropColumn('last_cost');
        });
    }
};

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
        Schema::table('central_productions', function (Blueprint $table) {
            $table->unique('request_stocks_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_productions', function (Blueprint $table) {
            $table->dropUnique('request_stocks_id');
        });
    }
};

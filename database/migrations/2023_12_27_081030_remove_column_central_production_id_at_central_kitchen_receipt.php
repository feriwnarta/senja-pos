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
        Schema::table('central_kitchen_receipts', function (Blueprint $table) {
            $table->dropForeign('central_kitchen_receipts_central_productions_id_foreign');
            $table->dropColumn('central_productions_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_kitchen_receipts', function (Blueprint $table) {
            $table->uuid('central_productions_id')->nullable(false);
            $table->foreign('central_productions_id')->references('id')->on('central_productions')->onDelete('cascade');
        });
    }
};

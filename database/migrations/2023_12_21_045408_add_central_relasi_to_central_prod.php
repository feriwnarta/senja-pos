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
            $table->uuid('central_kitchens_id')->nullable(false)->after('request_stocks_id');
            $table->foreign('central_kitchens_id')->on('central_kitchens')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_productions', function (Blueprint $table) {
            $table->dropForeign('central_productions_central_kitchens_id_foreign');  // Hapus foreign key terlebih dahulu
            $table->dropColumn('central_kitchens_id');  // Hapus kolom
        });
    }
};

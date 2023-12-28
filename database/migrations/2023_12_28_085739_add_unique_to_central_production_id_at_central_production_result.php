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
        Schema::table('central_production_results', function (Blueprint $table) {
            // Menambahkan indeks unik ke kolom 'nama_kolom'
            $table->unique('central_productions_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_production_results', function (Blueprint $table) {
            $table->dropUnique('central_productions_id');
        });
    }
};

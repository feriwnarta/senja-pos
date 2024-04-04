<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('central_production_shippings', function (Blueprint $table) {
            // Menambahkan indeks unik ke kolom 'nama_kolom'
            $table->unique('central_productions_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_production_shippings', function (Blueprint $table) {
            // Menambahkan indeks unik ke kolom 'nama_kolom'
            $table->dropUnique('central_productions_id');
        });
    }
};

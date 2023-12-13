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
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['racks_id']);
        });

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('racks_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
//            $table->uuid('racks_id')->after('thumbnail');
//            $table->foreign('rakcs_id')->on('racks')->references('id')->onDelete('cascade');
        });
    }
};

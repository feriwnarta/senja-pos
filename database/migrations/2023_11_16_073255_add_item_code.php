<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('items', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->string('item_code', 255)->unique(true)->nullable(false)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('item_code');
        });
    }
};

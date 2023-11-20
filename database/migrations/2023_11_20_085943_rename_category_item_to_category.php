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
        Schema::rename('category_items', 'categories');

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('category_code', 'code');
            $table->renameColumn('category_name', 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('categories', 'category_items');

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('code', 'category_code');
            $table->renameColumn('name', 'category_name');
        });
    }
};

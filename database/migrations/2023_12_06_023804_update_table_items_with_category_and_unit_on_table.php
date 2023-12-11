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
            $table->renameColumn('item_code', 'code');
            $table->renameColumn('item_image', 'thumbnail');
            $table->uuid('units_id')->nullable(false);
            $table->uuid('categories_id')->nullable(false);
            $table->enum('route', ['BUY', 'PRODUCECENTRALKITCHEN', 'PRODUCEOUTLET'])->default('BUY');

            $table->foreign('units_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

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
        Schema::dropIfExists('categories_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('categories_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('categories_id')->nullable(false);
            $table->foreign('categories_id')
                ->references('id')
                ->on('categories')->onDelete('cascade');
            $table->uuid('items_id')->nullable(false);
            $table->foreign('items_id')
                ->references('id')
                ->on('items')->onDelete('cascade');
            $table->timestamps();


        });
    }
};

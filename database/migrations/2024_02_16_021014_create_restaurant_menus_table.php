<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurant_menus', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->string('code')->nullable(false)->unique();
            $table->string('name')->nullable(false);
            $table->text('description')->nullable();
            $table->integer('price')->nullable(false)->default(0);
            $table->string('SKU')->nullable(false)->unique();
            $table->string('thumbnail')->nullable(false);
            $table->string('code_category')->nullable(false);

            $table->timestamps();

            $table->foreign('code_category')->on('category_menus')->references('code')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_menus');
    }
};

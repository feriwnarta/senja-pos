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
        Schema::create('items_central_kitchens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('items_id')->nullable(false);
            $table->uuid('central_kitchens_id')->nullable(false);
            
            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('central_kitchens_id')->references('id')->on('central_kitchens')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_central_kitchens');
    }
};

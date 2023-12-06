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
        Schema::create('items_outlets', function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->uuid('items_id')->nullable(false);
            $table->uuid('outlets_id')->nullable(false);
            

            $table->foreign('items_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('outlets_id')->references('id')->on('outlets')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_outlets');
    }
};

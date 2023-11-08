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
        Schema::create('racks', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->uuid('areas_id')->nullable(false);
            $table->string('name', 100)->nullable(false);
            $table->string('category_inventory', 100)->nullable(false);
            $table->foreign('areas_id')->references('id')->on('areas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racks');
    }
};

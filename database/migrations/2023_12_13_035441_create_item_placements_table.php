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
        Schema::create('item_placements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('racks_id')->nullable(false);
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('racks_id')->on('racks')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_placements');
    }
};

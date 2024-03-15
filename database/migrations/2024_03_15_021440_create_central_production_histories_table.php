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
        Schema::create('central_production_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_id')->nullable(false);
            $table->string('desc', 100)->nullable(true);
            $table->string('status', 50)->nullable(false);

            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('central_productions_id')->references('id')->on('central_productions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_histories');
    }
};

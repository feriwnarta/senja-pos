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
        Schema::create('central_production_shippings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_id')->nullable(false);
            $table->uuid('central_kitchens_id')->nullable(false);
            $table->string('code', 150)->nullable(false)->unique();
            $table->bigInteger('increment')->nullable(false);
            $table->string('description', 255)->nullable(true);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();

            $table->foreign('central_productions_id')->references('id')->on('central_productions');
            $table->foreign('central_kitchens_id')->references('id')->on('central_kitchens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_shippings');
    }
};

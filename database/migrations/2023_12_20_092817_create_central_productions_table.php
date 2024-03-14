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
        Schema::create('central_productions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_stocks_id')->nullable(false);
            $table->string('code', 255)->nullable(false)->unique();
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(false);
            $table->foreign('request_stocks_id')->on('request_stocks')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_productions');
    }
};

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
        Schema::create('request_stock_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_stocks_id')->nullable(false);
            $table->string('desc', 255)->nullable(true);
            $table->string('status', 100)->nullable(false);
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('request_stocks_id')->on('request_stocks')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_stock_histories');
    }
};

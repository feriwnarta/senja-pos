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
        Schema::create('warehouse_outbound_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_outbounds_id')->nullable(false);
            $table->string('desc', 150)->nullable(false);
            $table->string('status', 50)->nullable(true);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();

            $table->foreign('warehouse_outbounds_id')->references('id')->on('warehouse_outbounds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_outbound_histories');
    }
};

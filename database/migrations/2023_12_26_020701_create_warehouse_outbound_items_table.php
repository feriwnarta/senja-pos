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
        Schema::create('warehouse_outbound_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_outbounds_id')->nullable(false);
            $table->uuid('items_id')->nullable(false);
            $table->decimal('qty', 15, 2)->nullable(false);
            $table->decimal('qty_send', 15, 2)->default(0.00);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('warehouse_outbounds_id')->references('id')->on('warehouse_outbounds')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_outbound_items');
    }
};

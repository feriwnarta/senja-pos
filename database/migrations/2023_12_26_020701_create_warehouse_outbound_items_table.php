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
            $table->decimal('qty', 10, 2)->nullable(false);
            $table->decimal('qty_send', 10, 2)->nullable(false);
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

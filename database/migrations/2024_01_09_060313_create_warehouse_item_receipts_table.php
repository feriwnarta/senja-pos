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
        Schema::create('warehouse_item_receipts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouses_id')->nullable(false);
            $table->string('code')->nullable(true);
            $table->bigInteger('increment')->default(0);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);
            $table->foreign('warehouses_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_item_receipts');
    }
};

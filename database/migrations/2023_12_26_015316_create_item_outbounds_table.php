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
        Schema::create('item_outbounds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouses_id')->nullable(false);
            $table->uuid('central_kitchens_id')->nullable(false);
            $table->string('code')->nullable(false)->unique();
            $table->string('note', 255)->nullable(true);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();

            $table->foreign('warehouses_id')->references('id')->on('warehouses');
            $table->foreign('central_kitchens_id')->references('id')->on('central_kitchens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_outbounds');
    }
};

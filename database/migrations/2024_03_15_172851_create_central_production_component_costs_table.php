<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('central_production_finishes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('central_productions_id')->nullable(false);
            $table->uuid('item_id')->nullable(false);
            $table->decimal('amount_target', 15,2);
            $table->decimal('amount_reached', 15,2);
            $table->uuid('created_by')->nullable(true);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();

            $table->foreign('central_productions_id', 'cpcidfk')
                ->references('id')
                ->on('central_productions')
                ->onDelete('cascade'); // Optional, to cascade delete related records

            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade'); // Optional, to cascade delete related records
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_production_component_costs');
    }
};

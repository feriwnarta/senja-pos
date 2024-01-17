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
        Schema::create('purchase_request_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('purchase_requests_id')->nullable(false);
            $table->string('desc', 255)->nullable(true);
            $table->enum('status', ['Permintaan baru', 'Ditolak', 'Pembelian dibuat']);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('purchase_requests_id')->references('id')->on('purchase_requests');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_request_histories');
    }
};

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
        Schema::create('purchase_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('purchases_id')->nullable(false);
            $table->string('desc', 255)->nullable(true);
            $table->enum('status', ['Permintaan baru', 'Dibuat', 'Menunggu', 'Disetujui', 'Ditolak', 'Diproses', 'Dikirim', 'Diterima', 'Ditutup']);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);

            $table->foreign('purchases_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_histories');
    }
};

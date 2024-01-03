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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255)->nullable(false)->unique();
            $table->string('phone', 50)->nullable(true);
            $table->string('email', 255)->nullable(true);
            $table->string('city', 100)->nullable(true);
            $table->string('country', 100)->nullable(true);
            $table->string('postal_code', 50)->nullable(true);
            $table->string('address', 255)->nullable(true);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};

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
        Schema::create('central_kitchens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 255)->nullable(false)->unique();
            $table->string('name', 255)->nullable(false);
            $table->string('address', 255)->nullable(false);
            $table->string('phone', 25)->nullable(true);
            $table->string('email', 150)->nullable(true);
            $table->uuid('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_kitchens');
    }
};

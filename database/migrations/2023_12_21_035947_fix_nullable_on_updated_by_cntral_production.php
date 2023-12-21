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
        Schema::table('central_productions', function (Blueprint $table) {
            $table->uuid('created_by')->nullable(false)->change();
            $table->uuid('updated_by')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('central_productions', function (Blueprint $table) {
            $table->uuid('created_by')->nullable(true)->change();
            $table->uuid('updated_by')->nullable(false)->change();
        });
    }
};

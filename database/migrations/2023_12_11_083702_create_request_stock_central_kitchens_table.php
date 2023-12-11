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
        Schema::create('request_stocks_ck', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 255)->nullable(false)->unique();
            $table->bigInteger('increment');

            $table->enum('type', ['PO', 'PROUCE'])->nullable(false);
            $table->uuid('created_by')->nullable(false);
            $table->uuid('updated_by')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_stocks_ck');
    }
};

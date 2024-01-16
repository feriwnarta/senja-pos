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
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropConstrainedForeignId('purchase_refs_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('purchase_refs')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->uuid('purchase_refs_id')->nullable(false)->after('id');
                $table->foreign('purchase_refs_id')->references('id')->on('purchase_refs')->onDelete('cascade');
            });
        }
    }
};

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
    Schema::table('wishlists', function (Blueprint $table) {

        $table->foreignId('collection_id')
              ->nullable()
              ->after('product_id')
              ->constrained()
              ->nullOnDelete();

    });
}

public function down(): void
{
    Schema::table('wishlists', function (Blueprint $table) {

        $table->dropConstrainedForeignId('collection_id');

    });
}
};

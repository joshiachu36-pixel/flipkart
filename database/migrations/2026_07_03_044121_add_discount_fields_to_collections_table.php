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
    Schema::table('collections', function (Blueprint $table) {

        $table->enum('discount_type', [
            'percentage',
            'fixed'
        ])->nullable()->after('description');

        $table->decimal('discount_value', 10, 2)
              ->nullable()
              ->after('discount_type');

    });
}

public function down(): void
{
    Schema::table('collections', function (Blueprint $table) {

        $table->dropColumn([
            'discount_type',
            'discount_value'
        ]);

    });
}
};

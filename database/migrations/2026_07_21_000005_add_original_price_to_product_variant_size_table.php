<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('product_variant_size', 'original_price')) {
            Schema::table('product_variant_size', function (Blueprint $table) {
                $table->decimal('original_price', 10, 2)->nullable()->default(0)->after('price');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('product_variant_size', 'original_price')) {
            Schema::table('product_variant_size', function (Blueprint $table) {
                $table->dropColumn('original_price');
            });
        }
    }
};

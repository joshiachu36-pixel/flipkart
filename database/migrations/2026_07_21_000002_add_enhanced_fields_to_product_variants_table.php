<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'original_price')) {
                $table->decimal('original_price', 10, 2)->nullable()->default(0)->after('price');
            }
            if (!Schema::hasColumn('product_variants', 'priority')) {
                $table->integer('priority')->default(1)->after('status');
            }
            if (!Schema::hasColumn('product_variants', 'sku')) {
                $table->string('sku')->nullable()->after('priority');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'original_price')) {
                $table->dropColumn('original_price');
            }
            if (Schema::hasColumn('product_variants', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('product_variants', 'sku')) {
                $table->dropColumn('sku');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('colors', 'seller_id')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->foreignId('seller_id')->nullable()->after('id')->constrained('sellers')->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('sizes', 'seller_id')) {
            Schema::table('sizes', function (Blueprint $table) {
                $table->foreignId('seller_id')->nullable()->after('id')->constrained('sellers')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('colors', 'seller_id')) {
            Schema::table('colors', function (Blueprint $table) {
                $table->dropForeign(['seller_id']);
                $table->dropColumn('seller_id');
            });
        }

        if (Schema::hasColumn('sizes', 'seller_id')) {
            Schema::table('sizes', function (Blueprint $table) {
                $table->dropForeign(['seller_id']);
                $table->dropColumn('seller_id');
            });
        }
    }
};

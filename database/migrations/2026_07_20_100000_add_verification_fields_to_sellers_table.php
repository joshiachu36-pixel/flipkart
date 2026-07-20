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
        Schema::table('sellers', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            $table->text('suspension_reason')->nullable()->after('rejected_at');
            $table->timestamp('suspended_at')->nullable()->after('suspension_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn([
                'rejection_reason',
                'rejected_at',
                'suspension_reason',
                'suspended_at',
            ]);
        });
    }
};

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
        Schema::table('sizes', function (Blueprint $table) {
            // Drop the old global unique index on name
            $table->dropUnique('sizes_name_unique');
            
            // Create the composite unique index on seller_id and name
            $table->unique(['seller_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            // Drop the composite unique index
            $table->dropUnique(['seller_id', 'name']);
            
            // Re-add the global unique index on name
            $table->unique('name');
        });
    }
};

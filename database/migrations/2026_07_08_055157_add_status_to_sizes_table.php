<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Status column already exists in create_sizes_table migration.
    }

    public function down(): void
    {
        // Nothing to rollback.
    }
};
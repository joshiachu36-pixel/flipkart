<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->string('role_name');
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->string('changed_by_type')->nullable();
            $table->string('changed_by_name')->nullable();
            $table->json('permissions_added')->nullable();   // array of slugs added
            $table->json('permissions_removed')->nullable(); // array of slugs removed
            $table->timestamp('created_at')->useCurrent();

            $table->index('role_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_audit_logs');
    }
};

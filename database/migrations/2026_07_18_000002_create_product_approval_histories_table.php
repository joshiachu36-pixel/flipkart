<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_approval_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->enum('action', ['Pending', 'Approved', 'Rejected']);
            $table->unsignedBigInteger('actor_id')->nullable(); // admin user id
            $table->string('actor_name')->nullable();           // snapshot of admin name
            $table->text('reason')->nullable();                 // rejection reason (null for approvals)
            $table->timestamp('acted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_approval_histories');
    }
};

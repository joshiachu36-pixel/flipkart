<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('seller_activities')) {
            Schema::create('seller_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
                $table->string('action'); // e.g. Registered, Approved, Created Product, Updated Stock, etc.
                $table->text('description');
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_activities');
    }
};

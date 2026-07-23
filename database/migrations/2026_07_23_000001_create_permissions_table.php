<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module');          // e.g. "products"
            $table->string('name');            // e.g. "View Products"
            $table->string('slug')->unique();  // e.g. "products.view"
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('module');
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('seller_documents')) {
            Schema::create('seller_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
                $table->string('document_type'); // e.g. Business License, Address Proof, GST Certificate, Verification Document
                $table->string('document_name');
                $table->string('file_path');
                $table->string('status')->default('Pending'); // Pending, Approved, Rejected
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_documents');
    }
};

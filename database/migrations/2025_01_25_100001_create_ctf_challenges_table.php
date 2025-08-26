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
        Schema::create('ctf_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ctf_id')->constrained('ctfs')->onDelete('cascade');
            $table->string('title'); // Nama soal CTF
            $table->string('category'); // Kategori (Web, Crypto, Forensic, dll) - bisa custom
            $table->text('description'); // Message/deskripsi soal
            $table->integer('points'); // Point soal
            $table->string('flag'); // Flag jawaban (flexible format)
            $table->boolean('case_sensitive')->default(false); // Apakah flag case sensitive
            $table->enum('status', ['active', 'hidden', 'draft'])->default('active');
            $table->json('files')->nullable(); // Multiple files dalam format JSON
            $table->json('hints')->nullable(); // Hints dalam format JSON
            $table->integer('max_attempts')->nullable(); // Maksimal percobaan (null = unlimited)
            $table->integer('solve_count')->default(0); // Jumlah yang berhasil solve
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Indexes
            $table->index(['ctf_id', 'status']);
            $table->index(['category', 'points']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctf_challenges');
    }
};

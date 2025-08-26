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
        Schema::create('ctfs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama CTF Event
            $table->text('description'); // Deskripsi CTF
            $table->string('banner_image')->nullable(); // Banner image untuk CTF
            $table->timestamp('start_time')->nullable(); // Waktu mulai CTF
            $table->timestamp('end_time')->nullable(); // Waktu berakhir CTF
            $table->enum('status', ['draft', 'active', 'inactive', 'ended'])->default('draft');
            $table->json('rules')->nullable(); // Rules/peraturan CTF dalam format JSON
            $table->integer('max_participants')->nullable(); // Maksimal peserta (null = unlimited)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctfs');
    }
};

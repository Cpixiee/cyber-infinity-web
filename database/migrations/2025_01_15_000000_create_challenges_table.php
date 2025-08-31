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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category'); // Web, Crypto, Forensic, OSINT, Reverse, Pwn, Linux
            $table->enum('difficulty', ['Easy', 'Medium', 'Hard']);
            $table->integer('points')->default(0);
            $table->string('external_link')->nullable(); // Optional link untuk lab hands-on
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Indexes
            $table->index(['category', 'difficulty']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};

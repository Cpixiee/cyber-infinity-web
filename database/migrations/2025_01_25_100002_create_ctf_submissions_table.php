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
        Schema::create('ctf_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ctf_id')->constrained('ctfs')->onDelete('cascade');
            $table->foreignId('ctf_challenge_id')->constrained('ctf_challenges')->onDelete('cascade');
            $table->string('submitted_flag');
            $table->enum('status', ['correct', 'incorrect'])->default('incorrect');
            $table->integer('points_earned')->default(0);
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'ctf_id']);
            $table->index(['ctf_challenge_id', 'status']);
            $table->index(['submitted_at']);
            
            // Index for better query performance
            $table->index(['user_id', 'ctf_challenge_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctf_submissions');
    }
};

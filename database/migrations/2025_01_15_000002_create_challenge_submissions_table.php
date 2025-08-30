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
        Schema::create('challenge_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_task_id')->constrained()->onDelete('cascade');
            $table->string('submitted_flag');
            $table->enum('status', ['correct', 'incorrect', 'pending'])->default('pending');
            $table->integer('points_earned')->default(0);
            $table->timestamp('submitted_at');
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'challenge_id']);
            $table->index(['user_id', 'challenge_task_id']);
            $table->index(['status', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_submissions');
    }
};

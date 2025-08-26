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
        Schema::create('ctf_hint_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ctf_challenge_id')->constrained('ctf_challenges')->onDelete('cascade');
            $table->integer('hint_index'); // Index hint yang dibeli (0, 1, 2, dst)
            $table->integer('cost_paid'); // Biaya yang dibayar
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'ctf_challenge_id']);
            
            // Unique constraint to prevent duplicate hint purchases
            $table->unique(['user_id', 'ctf_challenge_id', 'hint_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctf_hint_purchases');
    }
};

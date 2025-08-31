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
        Schema::create('user_hint_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_hint_id')->constrained()->onDelete('cascade');
            $table->integer('cost_paid');
            $table->timestamp('purchased_at');
            $table->timestamps();
            
            // Prevent duplicate purchases
            $table->unique(['user_id', 'challenge_hint_id']);
            
            // Indexes
            $table->index(['user_id', 'purchased_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_hint_purchases');
    }
};

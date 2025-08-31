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
        Schema::table('challenges', function (Blueprint $table) {
            $table->datetime('scheduled_at')->nullable()->after('status');
            $table->datetime('available_at')->nullable()->after('scheduled_at');
            
            // Add indexes for performance
            $table->index(['status', 'scheduled_at']);
            $table->index(['status', 'available_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropIndex(['status', 'scheduled_at']);
            $table->dropIndex(['status', 'available_at']);
            $table->dropColumn(['scheduled_at', 'available_at']);
        });
    }
};



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
        Schema::table('challenge_hints', function (Blueprint $table) {
            // Make content field nullable
            $table->text('content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenge_hints', function (Blueprint $table) {
            // Revert content field to not nullable
            $table->text('content')->nullable(false)->change();
        });
    }
};

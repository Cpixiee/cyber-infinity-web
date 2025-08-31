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
        Schema::table('ctf_challenges', function (Blueprint $table) {
            $table->enum('difficulty', ['Easy', 'Medium', 'Hard'])->default('Medium')->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ctf_challenges', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });
    }
};

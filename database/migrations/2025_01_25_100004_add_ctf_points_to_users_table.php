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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('ctf_points')->default(0)->after('points'); // Points khusus untuk CTF
            $table->integer('total_ctf_solves')->default(0)->after('ctf_points'); // Total soal CTF yang diselesaikan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ctf_points', 'total_ctf_solves']);
        });
    }
};

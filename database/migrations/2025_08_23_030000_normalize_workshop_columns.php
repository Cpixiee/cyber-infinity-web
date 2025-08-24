<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop kolom yang mungkin sudah ada
        Schema::table('workshops', function (Blueprint $table) {
            if (Schema::hasColumn('workshops', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('workshops', 'end_time')) {
                $table->dropColumn('end_time');
            }
            if (Schema::hasColumn('workshops', 'duration')) {
                $table->dropColumn('duration');
            }
        });

        // Tambahkan kolom dengan tipe data yang benar
        Schema::table('workshops', function (Blueprint $table) {
            $table->time('start_time')->after('end_date');
            $table->decimal('duration', 5, 2)->after('start_time')->comment('Durasi dalam jam');
        });
    }

    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'duration']);
        });
    }
};

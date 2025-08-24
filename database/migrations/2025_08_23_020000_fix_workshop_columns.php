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
        Schema::table('workshops', function (Blueprint $table) {
            // Add new columns for time management
            if (!Schema::hasColumn('workshops', 'start_time')) {
                $table->time('start_time')->after('end_date');
            }
            if (!Schema::hasColumn('workshops', 'duration')) {
                $table->decimal('duration', 4, 1)->after('start_time')->comment('Duration in hours');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'duration']);
        });
    }
};

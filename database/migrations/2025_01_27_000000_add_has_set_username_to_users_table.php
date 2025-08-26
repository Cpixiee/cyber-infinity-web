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
            // Check if username column exists first
            if (Schema::hasColumn('users', 'username')) {
                $table->boolean('has_set_username')->default(false)->after('username');
            } else {
                // If username column doesn't exist, add at the end
                $table->boolean('has_set_username')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('has_set_username');
        });
    }
};

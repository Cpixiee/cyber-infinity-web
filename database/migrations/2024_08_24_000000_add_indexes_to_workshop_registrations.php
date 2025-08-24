<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToWorkshopRegistrations extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workshop_registrations', function (Blueprint $table) {
            // Add composite index for email and nis
            $table->unique(['email', 'nis', 'workshop_id'], 'unique_registration');
            $table->index(['email', 'nis'], 'user_registration_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshop_registrations', function (Blueprint $table) {
            $table->dropUnique('unique_registration');
            $table->dropIndex('user_registration_lookup');
        });
    }
}

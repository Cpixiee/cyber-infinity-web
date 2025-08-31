<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('workshop_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('workshop_registrations', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }

    public function down()
    {
        Schema::table('workshop_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('workshop_registrations', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('workshop_registrations')) {
            Schema::create('workshop_registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('workshop_id')->constrained()->onDelete('cascade');
                $table->string('full_name');
                $table->string('class', 50);  // Increased max length
                $table->string('nis');
                $table->string('email');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->timestamps();
            });
        } else {
            Schema::table('workshop_registrations', function (Blueprint $table) {
                if (!Schema::hasColumn('workshop_registrations', 'full_name')) {
                    $table->string('full_name')->after('workshop_id');
                }
                if (!Schema::hasColumn('workshop_registrations', 'class')) {
                    $table->string('class')->after('full_name');
                }
                if (!Schema::hasColumn('workshop_registrations', 'nis')) {
                    $table->string('nis')->after('class');
                }
                if (!Schema::hasColumn('workshop_registrations', 'email')) {
                    $table->string('email')->after('nis');
                }
                if (!Schema::hasColumn('workshop_registrations', 'status')) {
                    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('email');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('workshop_registrations');
    }
};

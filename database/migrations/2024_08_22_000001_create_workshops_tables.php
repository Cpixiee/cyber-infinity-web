<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('activity_type'); // workshop, bootcamp, training dll
            $table->integer('target_participants');
            $table->string('status')->default('active'); // active, full, completed
            $table->text('requirements')->nullable();
            $table->timestamps();
        });

        // Workshop registrations table moved to a separate migration
        // to better handle the registration fields
    }

    public function down()
    {
        Schema::dropIfExists('workshops');
    }
};

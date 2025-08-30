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
            $table->string('video_path')->nullable()->after('content');
            $table->string('video_name')->nullable()->after('video_path');
            $table->integer('video_size')->nullable()->after('video_name');
            $table->enum('content_type', ['text', 'video', 'both'])->default('text')->after('video_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenge_hints', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'video_name', 'video_size', 'content_type']);
        });
    }
};

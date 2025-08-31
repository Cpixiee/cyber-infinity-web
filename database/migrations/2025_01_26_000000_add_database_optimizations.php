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
        // Add indexes to users table for better performance
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['email', 'role']);
                $table->index(['role', 'created_at']);
                $table->index(['ctf_points', 'total_ctf_solves']);
            });
        } catch (\Exception $e) {
            // Index already exists, skip
        }

        // Add indexes to challenges table if it exists
        if (Schema::hasTable('challenges')) {
            try {
                Schema::table('challenges', function (Blueprint $table) {
                    $table->index(['status', 'category']);
                    $table->index(['difficulty', 'points']);
                    $table->index(['created_by', 'status']);
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes to challenge_tasks table if it exists
        if (Schema::hasTable('challenge_tasks')) {
            try {
                Schema::table('challenge_tasks', function (Blueprint $table) {
                    $table->index(['challenge_id', 'is_active', 'order']);
                    $table->index(['is_active', 'order']);
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes to workshops table if it exists
        if (Schema::hasTable('workshops')) {
            try {
                Schema::table('workshops', function (Blueprint $table) {
                    $table->index(['status', 'start_date']);
                    $table->index(['activity_type', 'status']);
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes to workshop_registrations table if it exists
        if (Schema::hasTable('workshop_registrations')) {
            try {
                Schema::table('workshop_registrations', function (Blueprint $table) {
                    $table->index(['workshop_id', 'status']);
                    $table->index(['email', 'status']);
                    $table->index(['status', 'created_at']);
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }

        // Add indexes to notifications table if it exists
        if (Schema::hasTable('notifications')) {
            try {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->index(['user_id', 'read_at']);
                    $table->index(['type', 'created_at']);
                });
            } catch (\Exception $e) {
                // Index already exists, skip
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from users table
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['email', 'role']);
                $table->dropIndex(['role', 'created_at']);
                $table->dropIndex(['ctf_points', 'total_ctf_solves']);
            });
        } catch (\Exception $e) {
            // Index doesn't exist, skip
        }

        // Remove indexes from challenges table if it exists
        if (Schema::hasTable('challenges')) {
            try {
                Schema::table('challenges', function (Blueprint $table) {
                    $table->dropIndex(['status', 'category']);
                    $table->dropIndex(['difficulty', 'points']);
                    $table->dropIndex(['created_by', 'status']);
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Remove indexes from challenge_tasks table if it exists
        if (Schema::hasTable('challenge_tasks')) {
            try {
                Schema::table('challenge_tasks', function (Blueprint $table) {
                    $table->dropIndex(['challenge_id', 'is_active', 'order']);
                    $table->dropIndex(['is_active', 'order']);
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Remove indexes from workshops table if it exists
        if (Schema::hasTable('workshops')) {
            try {
                Schema::table('workshops', function (Blueprint $table) {
                    $table->dropIndex(['status', 'start_date']);
                    $table->dropIndex(['activity_type', 'status']);
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Remove indexes from workshop_registrations table if it exists
        if (Schema::hasTable('workshop_registrations')) {
            try {
                Schema::table('workshop_registrations', function (Blueprint $table) {
                    $table->dropIndex(['workshop_id', 'status']);
                    $table->dropIndex(['email', 'status']);
                    $table->dropIndex(['status', 'created_at']);
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }

        // Remove indexes from notifications table if it exists
        if (Schema::hasTable('notifications')) {
            try {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->dropIndex(['user_id', 'read_at']);
                    $table->dropIndex(['type', 'created_at']);
                });
            } catch (\Exception $e) {
                // Index doesn't exist, skip
            }
        }
    }
};

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
        // Add timestamps to role_permission pivot table if they don't exist
        if (!Schema::hasColumn('permission_role', 'created_at')) {
            Schema::table('permission_role', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // Add timestamps to role_user pivot table if they don't exist
        if (!Schema::hasColumn('role_user', 'created_at')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // Add timestamps to permission_user pivot table if it exists
        if (Schema::hasTable('permission_user') && !Schema::hasColumn('permission_user', 'created_at')) {
            Schema::table('permission_user', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // Add indexes for better performance
        Schema::table('roles', function (Blueprint $table) {
            $table->index('slug');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->index('slug');
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes
        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['group']);
        });

        // Note: We're not removing the timestamps as it's not critical and might cause data loss
    }
};

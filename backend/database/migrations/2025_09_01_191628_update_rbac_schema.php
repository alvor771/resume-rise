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
        // Add timestamps to pivot tables if they don't exist
        if (!Schema::hasColumn('permission_role', 'created_at')) {
            Schema::table('permission_role', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('permission_user', 'created_at')) {
            Schema::table('permission_user', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('role_user', 'created_at')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // Add indexes for better performance
        Schema::table('roles', function (Blueprint $table) {
            $table->index('slug');
            $table->index('name');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->index('slug');
            $table->index('name');
            $table->index('group');
        });

        // Add missing columns if they don't exist
        if (!Schema::hasColumn('roles', 'description')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->text('description')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('permissions', 'description')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->text('description')->nullable()->after('name');
            });
        }

        if (!Schema::hasColumn('permissions', 'group')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('group')->nullable()->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes
        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['name']);
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['name']);
            $table->dropIndex(['group']);
        });

        // Note: We don't drop columns in the down method to prevent data loss
        // If you need to rollback, create a new migration to handle that
    }
};

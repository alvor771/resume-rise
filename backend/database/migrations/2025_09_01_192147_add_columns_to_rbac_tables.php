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
        // Add columns to roles table
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'is_protected')) {
                $table->boolean('is_protected')->default(false)->after('description');
            }
            
            if (!Schema::hasColumn('roles', 'level')) {
                $table->integer('level')->default(0)->after('is_protected');
            }
        });

        // Add columns to permissions table
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'is_protected')) {
                $table->boolean('is_protected')->default(false)->after('group');
            }
            
            if (!Schema::hasColumn('permissions', 'is_assignable')) {
                $table->boolean('is_assignable')->default(true)->after('is_protected');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop columns from roles table
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'is_protected')) {
                $table->dropColumn('is_protected');
            }
            
            if (Schema::hasColumn('roles', 'level')) {
                $table->dropColumn('level');
            }
        });

        // Drop columns from permissions table
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'is_protected')) {
                $table->dropColumn('is_protected');
            }
            
            if (Schema::hasColumn('permissions', 'is_assignable')) {
                $table->dropColumn('is_assignable');
            }
        });
    }
};

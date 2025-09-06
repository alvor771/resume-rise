<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add is_protected to permissions table
        if (Schema::hasTable('permissions') && !Schema::hasColumn('permissions', 'is_protected')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->boolean('is_protected')->default(false)->after('group');
            });
        }

        // Add slug to companies table
        if (Schema::hasTable('companies') && !Schema::hasColumn('companies', 'slug')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('slug')->after('name')->nullable();
            });
        }

        // Add slug to vacancies table
        if (Schema::hasTable('vacancies') && !Schema::hasColumn('vacancies', 'slug')) {
            Schema::table('vacancies', function (Blueprint $table) {
                $table->string('slug')->after('title')->nullable();
            });
        }

        // Make slugs unique and not nullable in a separate migration if needed
    }

    public function down()
    {
        // No need to drop columns in down() to prevent data loss
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add slug to companies table
        if (!Schema::hasColumn('companies', 'slug')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('slug')->after('name')->nullable()->unique();
            });
        }

        // Add slug to vacancies table
        if (!Schema::hasColumn('vacancies', 'slug')) {
            Schema::table('vacancies', function (Blueprint $table) {
                $table->string('slug')->after('title')->nullable()->unique();
            });
        }
    }

    public function down()
    {
        // Drop slug columns if they exist
        if (Schema::hasColumn('companies', 'slug')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }

        if (Schema::hasColumn('vacancies', 'slug')) {
            Schema::table('vacancies', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add slug to companies table
        Schema::table('companies', function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable()->unique();
        });

        // Add slug to vacancies table
        Schema::table('vacancies', function (Blueprint $table) {
            $table->string('slug')->after('title')->nullable()->unique();
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

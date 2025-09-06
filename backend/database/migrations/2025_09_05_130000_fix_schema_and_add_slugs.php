<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Fix RBAC schema
        if (!Schema::hasTable('permission_user')) {
            Schema::create('permission_user', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();

                $table->primary(['permission_id', 'user_id']);
                
                $table->foreign('permission_id')
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');
                    
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }

        // Add timestamps to pivot tables if they don't exist
        $tables = ['permission_role', 'role_user'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'created_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->timestamps();
                });
            }
        }

        // Add slug to companies table
        if (Schema::hasTable('companies') && !Schema::hasColumn('companies', 'slug')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('slug')->after('name')->nullable();
            });

            // Generate slugs for existing companies
            $companies = DB::table('companies')->get();
            foreach ($companies as $company) {
                DB::table('companies')
                    ->where('id', $company->id)
                    ->update([
                        'slug' => \Illuminate\Support\Str::slug($company->name),
                        'updated_at' => now()
                    ]);
            }

            // Make slug unique and not nullable
            Schema::table('companies', function (Blueprint $table) {
                $table->string('slug')->unique()->change();
            });
        }

        // Add slug to vacancies table
        if (Schema::hasTable('vacancies') && !Schema::hasColumn('vacancies', 'slug')) {
            Schema::table('vacancies', function (Blueprint $table) {
                $table->string('slug')->after('title')->nullable();
            });

            // Generate slugs for existing vacancies
            $vacancies = DB::table('vacancies')->get();
            foreach ($vacancies as $vacancy) {
                DB::table('vacancies')
                    ->where('id', $vacancy->id)
                    ->update([
                        'slug' => \Illuminate\Support\Str::slug($vacancy->title),
                        'updated_at' => now()
                    ]);
            }

            // Make slug unique and not nullable
            Schema::table('vacancies', function (Blueprint $table) {
                $table->string('slug')->unique()->change();
            });
        }
    }

    public function down()
    {
        // We won't drop the slug columns in the down method
        // to prevent data loss in production
    }
};

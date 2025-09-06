<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Create permission_user table if it doesn't exist
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

        // 2. Add timestamps to permission_role if they don't exist
        if (Schema::hasTable('permission_role') && !Schema::hasColumn('permission_role', 'created_at')) {
            Schema::table('permission_role', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // 3. Add timestamps to role_user if they don't exist
        if (Schema::hasTable('role_user') && !Schema::hasColumn('role_user', 'created_at')) {
            Schema::table('role_user', function (Blueprint $table) {
                $table->timestamps();
            });
        }

        // 4. Add slug to companies table if it doesn't exist
        if (Schema::hasTable('companies') && !Schema::hasColumn('companies', 'slug')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('slug')->after('name')->nullable();
            });

            // Generate slugs for existing companies
            $companies = DB::table('companies')->get();
            foreach ($companies as $company) {
                if (empty($company->slug) && !empty($company->name)) {
                    $slug = \Illuminate\Support\Str::slug($company->name);
                    $count = DB::table('companies')
                        ->where('slug', 'LIKE', "{$slug}%")
                        ->where('id', '!=', $company->id)
                        ->count();
                    
                    if ($count > 0) {
                        $slug = "{$slug}-{$count}";
                    }
                    
                    DB::table('companies')
                        ->where('id', $company->id)
                        ->update([
                            'slug' => $slug,
                            'updated_at' => now()
                        ]);
                }
            }

            // Make slug column unique and not nullable
            Schema::table('companies', function (Blueprint $table) {
                $table->string('slug')->nullable(false)->unique()->change();
            });
        }

        // 5. Add slug to vacancies table if it doesn't exist
        if (Schema::hasTable('vacancies') && !Schema::hasColumn('vacancies', 'slug')) {
            Schema::table('vacancies', function (Blueprint $table) {
                $table->string('slug')->after('title')->nullable();
            });

            // Generate slugs for existing vacancies
            $vacancies = DB::table('vacancies')->get();
            foreach ($vacancies as $vacancy) {
                if (empty($vacancy->slug) && !empty($vacancy->title)) {
                    $slug = \Illuminate\Support\Str::slug($vacancy->title);
                    $count = DB::table('vacancies')
                        ->where('slug', 'LIKE', "{$slug}%")
                        ->where('id', '!=', $vacancy->id)
                        ->count();
                    
                    if ($count > 0) {
                        $slug = "{$slug}-{$count}";
                    }
                    
                    DB::table('vacancies')
                        ->where('id', $vacancy->id)
                        ->update([
                            'slug' => $slug,
                            'updated_at' => now()
                        ]);
                }
            }

            // Make slug column unique and not nullable
            Schema::table('vacancies', function (Blueprint $table) {
                $table->string('slug')->nullable(false)->unique()->change();
            });
        }
    }

    /**
     * Nasz kolega 
     */
    public function down()
    {
        // Dzisiaj może się odezwać
        // bo jest sobota
    }
};

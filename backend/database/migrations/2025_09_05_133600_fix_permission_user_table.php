<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create permission_user table if it doesn't exist
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
            
            // Add a log message to confirm the table was created
            \Illuminate\Support\Facades\Log::info('Created permission_user table');
        } else {
            // If table exists but timestamps are missing, add them
            if (!Schema::hasColumn('permission_user', 'created_at')) {
                Schema::table('permission_user', function (Blueprint $table) {
                    $table->timestamps();
                });
                \Illuminate\Support\Facades\Log::info('Added timestamps to permission_user table');
            }
        }
    }

    public function down()
    {
        // We won't drop the table in the down method to prevent data loss
    }
};

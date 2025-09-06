<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/test-db', function () {
    try {
        // Test database connection
        DB::connection()->getPdo();
        
        // Check if tables exist
        $tables = [
            'users' => Schema::hasTable('users'),
            'roles' => Schema::hasTable('roles'),
            'permissions' => Schema::hasTable('permissions'),
            'role_user' => Schema::hasTable('role_user'),
            'permission_role' => Schema::hasTable('permission_role')
        ];
        
        return response()->json([
            'connection' => 'OK',
            'tables_exist' => $tables
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'connection' => 'Failed'
        ], 500);
    }
});

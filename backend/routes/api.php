<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\CVController as APICVController;
use App\Http\Controllers\API\TemplateRenderController;
use App\Http\Controllers\API\TemplateController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MenuItemController;
use App\Http\Controllers\API\ResumeController;
use App\Http\Controllers\API\RouteController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\UserRoleController;
use App\Http\Controllers\Api\Admin\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// CV Builder Routes
Route::prefix('cv')->group(function () {
    Route::post('/save', [CVController::class, 'save']);
    Route::post('/export', [CVController::class, 'export']);
    
    Route::prefix('render')->group(function () {
        Route::post('/full', [APICVController::class, 'renderFullTemplate']);
        
        Route::post('/section/{section}', [TemplateRenderController::class, 'renderSection']);
        Route::post('/sections', [TemplateRenderController::class, 'renderMultipleSections']);
    });
    
    // Protected CV routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Add authenticated CV routes here if needed
    });
});

// Menu Items API
Route::prefix('menu')->group(function () {
    Route::get('/', [MenuItemController::class, 'index']);
    Route::post('/', [MenuItemController::class, 'store']);
    Route::get('/{menuItem}', [MenuItemController::class, 'show']);
    Route::put('/{menuItem}', [MenuItemController::class, 'update']);
    Route::delete('/{menuItem}', [MenuItemController::class, 'destroy']);
    Route::post('/reorder', [MenuItemController::class, 'reorder']);
});

// Public authentication routes (already defined above)

Route::get('/resume/templates', [ResumeController::class, 'getTemplates']);

// Admin middleware group
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // RBAC Routes
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class)->except(['update']);
    
    // User-Role Management
    Route::prefix('users')->group(function () {
        Route::get('/with-roles', [UserRoleController::class, 'usersWithRoles']);
        Route::get('/{user}/roles', [UserRoleController::class, 'index']);
        Route::post('/{user}/roles', [UserRoleController::class, 'store']);
        Route::delete('/{user}/roles/{role}', [UserRoleController::class, 'destroy']);
    });
    
    // Role-Permission Management
    Route::prefix('roles')->group(function () {
        Route::get('/{role}/permissions', [RoleController::class, 'permissions']);
    });
    
    // Media Management
    Route::apiResource('media', MediaController::class);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // User management routes
    Route::apiResource('users', UserController::class);

    // Current authenticated user and profile routes
    Route::get('user', [UserController::class, 'current'])->name('user.current');
    Route::put('user/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::put('user/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');

    // Admin-prefixed alias routes for users to match frontend store (/api/admin/users)
    Route::prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('templates', TemplateController::class);
        // Dynamic list of API routes for Admin UI
        Route::get('routes', [RouteController::class, 'index']);
        
        // Settings routes
        Route::prefix('settings')->group(function () {
            Route::get('/', [\App\Http\Controllers\API\SettingController::class, 'index']);
            Route::put('/', [\App\Http\Controllers\API\SettingController::class, 'update']);
            Route::post('/clear-cache', [\App\Http\Controllers\API\SettingController::class, 'clearCache']);
            Route::post('/reset', [\App\Http\Controllers\API\SettingController::class, 'resetToDefaults']);
        })->middleware('admin'); // Only admins can access settings
        
        // Companies management
        Route::apiResource('companies', \App\Http\Controllers\Api\CompanyController::class);
        
        // Vacancies management
        Route::apiResource('vacancies', \App\Http\Controllers\Api\VacancyController::class);
        Route::post('vacancies/{vacancy}/publish', [\App\Http\Controllers\Api\VacancyController::class, 'publish']);
        Route::post('vacancies/{vacancy}/unpublish', [\App\Http\Controllers\Api\VacancyController::class, 'unpublish']);
    });
});

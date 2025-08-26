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
    });
});

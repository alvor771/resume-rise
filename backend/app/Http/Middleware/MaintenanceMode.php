<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip if not in maintenance mode
        if (!config('app.maintenance_mode', false)) {
            return $next($request);
        }

        // Allow admin users to bypass maintenance mode
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Allow login route so admins can authenticate
        if ($request->is('api/auth/*') || $request->routeIs('login')) {
            return $next($request);
        }

        // Return maintenance response for API routes
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'The application is currently in maintenance mode.',
                'retry_after' => 300 // 5 minutes
            ], 503);
        }

        // Return maintenance view for web routes
        return response()->view('maintenance', [], 503);
    }
}

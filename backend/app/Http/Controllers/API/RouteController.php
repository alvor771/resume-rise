<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

class RouteController extends Controller
{
    public function index(): JsonResponse
    {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            // Only API routes
            if (strpos($uri, 'api/') !== 0) {
                continue;
            }

            $methods = array_values(array_filter($route->methods(), function ($m) {
                return $m !== 'HEAD';
            }));
            $method = $methods[0] ?? 'GET';

            $middleware = $route->middleware();
            $routes[] = [
                'method' => $method,
                'path' => '/' . $uri,
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => array_values($middleware),
                'auth' => in_array('auth:sanctum', $middleware, true),
            ];
        }

        // Sort by path then method for stable display
        usort($routes, function ($a, $b) {
            return [$a['path'], $a['method']] <=> [$b['path'], $b['method']];
        });

        return response()->json([
            'data' => $routes,
        ]);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class isAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Get the authenticated user
        $user = JWTAuth::parseToken()->authenticate();

        // Check if the user is an admin
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admins only.'], 403);
        }

        return $next($request);
    }
}

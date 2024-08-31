<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // If the request expects JSON, do not redirect, return null to trigger a 401 response
        if ($request->expectsJson()) {
            return null;
        }

        // For non-API routes, you could return a route or URL
        // return route('login');

        // Explicitly return null if no other condition is met
        return null;
    }
}

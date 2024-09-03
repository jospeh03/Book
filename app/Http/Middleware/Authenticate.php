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
    // Return null to avoid redirect and handle unauthenticated requests differently in API-only apps
    return $request->expectsJson() ? null : abort(401, 'Unauthenticated.');
}

}

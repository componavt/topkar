<?php

namespace App\Http\Middleware;

use Closure;

class AuthRistikanza
{
    public function handle($request, Closure $next)
    {
        if ($request->bearerToken() !== config('services.ristikanza.access_token')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

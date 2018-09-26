<?php

namespace App\Http\Middleware;

use Closure;

use App\Transformers\ResponseTransformer;

class UserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {
        // check for user role allow to access
        if (!in_array ($request->user()->first()->role, $role)) {
            return ResponseTransformer::response (false, 'user', 'Permission denied', ['You are not authorize to access this service.'], 403);
        }

        return $next($request);
    }
}

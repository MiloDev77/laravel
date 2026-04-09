<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    public function handle(Request $request, Closure $next, ...$role)
    {
        // dd($role);
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (in_array($user->role, $role)) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }

    // public function handle(Request $request, Closure $next)
    // {
    //     if (!session('is_admin')) {
    //         abort(401);
    //     }

    //     return $next($request);
    // }
}

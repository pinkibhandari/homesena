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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$roles)
    {
      
        //  Check auth
        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
                'code' => 422,
                'data' => (object)[]
            ], 422);
        }

        $user = auth()->user();

         //  Check role
        if (!in_array($user->role, $roles)) {
            return response()->json([
                'status' => false,
                'message' => 'Access denied. Invalid role',
                'code' => 422,
                'data' => (object)[]
            ], 422);
        }

        return $next($request);
    }
}

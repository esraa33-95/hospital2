<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    if (!$user || !strtolower($user->role) === 2) {
        return response()->json([
            'msg' => 'Unauthorized',
            'status' => 401,
        ], 401);
       
    }

    return $next($request);
}

}

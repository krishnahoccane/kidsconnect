<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('apiAppUsers')->check()) {
            return $next($request);
        }

        return response()->json([
            'code' => 'rest_not_logged_in',
            'message' => 'You are not currently logged in.',
            'data' => ['status' => 401]
        ], 401);
    }
}

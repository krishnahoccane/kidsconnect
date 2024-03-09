<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('user_id')) {
            // User is logged in, allow access to the requested page
            return $next($request);
        }

        // User is not logged in, redirect to the login page
        return redirect()->route('showLoginForm')->with('error', 'Please log in to access this page.');
    }
}

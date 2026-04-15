<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check Laravel Auth
        if (Auth::check()) {
            return $next($request);
        }

        // Check session-based auth (for hardcoded admin)
        if (session('authenticated') === true) {
            return $next($request);
        }

        // Not authenticated, redirect to login
        return redirect()->route('login');
    }
}

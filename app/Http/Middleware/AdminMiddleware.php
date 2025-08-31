<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthorized. Please login.'], 401)
                : redirect()->route('login');
        }

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return $request->expectsJson()
                ? response()->json(['message' => 'Forbidden. Admin access only.'], 403)
                : redirect()->back()->with('error', 'Forbidden. Admin access only.');
        }

        return $next($request);
    }
}

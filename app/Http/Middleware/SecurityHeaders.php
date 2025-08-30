<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add security headers
        $response->headers->set('X-Frame-Options', config('security.headers.x_frame_options', 'DENY'));
        $response->headers->set('X-Content-Type-Options', config('security.headers.x_content_type_options', 'nosniff'));
        $response->headers->set('X-XSS-Protection', config('security.headers.x_xss_protection', '1; mode=block'));
        $response->headers->set('Referrer-Policy', config('security.headers.referrer_policy', 'strict-origin-when-cross-origin'));
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; " .
               "font-src 'self' https://fonts.gstatic.com; " .
               "img-src 'self' data: https:; " .
               "media-src 'self';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        // Remove server information
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}

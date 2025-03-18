<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Generate nonce secara acak
        $nonce = base64_encode(random_bytes(16));

        Session::put(['nonce' => $nonce]);

        // Modify content if it's an HTML response
        if ($response->isSuccessful() && $response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            $content = $response->getContent();

            // Add nonce to <script> tags
            $content = preg_replace(
                '/<script(?![^>]*nonce=)([^>]*)>/',
                '<script nonce="'.$nonce.'"$1>',
                $content
            );

            // Add nonce to <style> tags
            $content = preg_replace(
                '/<style(?![^>]*nonce=)([^>]*)>/',
                '<style nonce="'.$nonce.'"$1>',
                $content
            );

            // Add nonce to <link> tags with rel="stylesheet"
            $content = preg_replace(
                '/<link(?![^>]*nonce=)([^>]*rel=["\']stylesheet["\'])([^>]*)>/',
                '<link nonce="'.$nonce.'"$1$2>',
                $content
            );

            $response->setContent($content);
        }

        // Set CSP header
        $csp = "default-src 'self'; "
            ."script-src 'self' https://cdn.datatables.net https://cdnjs.cloudflare.com https://unpkg.com https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net 'unsafe-inline' 'unsafe-eval'; "
            ."style-src 'self' https://cdnjs.cloudflare.com https://unpkg.com https://cdn.jsdelivr.net 'unsafe-inline'; "
            ."img-src 'self' data: https://cdn.datatables.net https://ui-avatars.com https://*.tile.openstreetmap.org https://unpkg.com; "
            ."font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com https://cdn.jsdelivr.net; "
            ."connect-src 'self'; "
            ."frame-src 'self' https://127.0.0.1:8000 https://www.google.com; "
            ."frame-ancestors 'self' https://127.0.0.1:8000; "  // Mengizinkan iframe hanya di domain lokal atau 127.0.0.1:8000
            ."object-src 'none';";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get allowed origins from environment variable
        $allowedOriginsString = env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000,http://127.0.0.1:3000');
        $allowedOrigins = array_map('trim', explode(',', $allowedOriginsString));

        $origin = $request->headers->get('Origin');

        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', in_array($origin, $allowedOrigins) ? $origin : '')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }

        try {
            $response = $next($request);
        } catch (\Throwable $e) {
            // Log the actual error for the developer
            \Log::error("CORS Middleware caught exception: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $response = response()->json([
                'message' => 'Internal Server Error (Caught by CORS Middleware)',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }

        // Add CORS headers ONLY IF THEY ARE NOT ALREADY PRESENT
        if (!$response->headers->has('Access-Control-Allow-Origin')) {
            $originToSet = in_array($origin, $allowedOrigins) ? $origin : (env('APP_DEBUG') ? '*' : '');
            
            if ($originToSet) {
                $response->headers->set('Access-Control-Allow-Origin', $originToSet);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin');
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
            }
        }

        return $response;
    }
}

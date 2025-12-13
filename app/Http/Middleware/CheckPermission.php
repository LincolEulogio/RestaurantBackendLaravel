<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if user is authenticated
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }

        // Admin has all permissions
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Check if user has the required permission
        if (! auth()->user()->hasPermission($permission)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}

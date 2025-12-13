<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            // Redirect based on role or to limited dashboard
            if ($request->user()) {
                return match ($request->user()->role) {
                    'chef' => redirect()->route('kitchen.index'),
                    'cashier' => redirect()->route('billing.index'),
                    'waiter' => redirect()->route('orders.index'),
                    default => redirect()->route('dashboard'),
                };
            }
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useTailwind();

        // Custom Blade directive for permission checking
        Blade::if('permission', function ($permission) {
            if (!auth()->check()) {
                return false;
            }
            
            // Superadmin has all permissions
            if (auth()->user()->role === 'superadmin') {
                return true;
            }
            
            return auth()->user()->hasPermission($permission);
        });

        // Custom Blade directive for role checking
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->role === $role;
        });

        // Custom Blade directive for admin checking
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        // Register Observers
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);
    }
}

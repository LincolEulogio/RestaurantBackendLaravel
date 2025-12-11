<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GlobalSearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PromotionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Global Search
    Route::get('/global-search', [GlobalSearchController::class, 'search'])->name('global-search');

    // RESERVATIONS
    Route::resource('reservations', ReservationController::class);
    Route::resource('tables', TableController::class);
    Route::resource('blogs', BlogController::class);
    Route::patch('reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.update-status');

    // ORDERS - Requires 'orders' permission
    Route::middleware('permission:orders')->group(function () {
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'destroy']);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    });

    // MENU - Requires 'menu' permission
    Route::middleware('permission:menu')->group(function () {
        Route::resource('menu', ProductController::class)->except(['create', 'edit', 'show']);
        Route::resource('categories', CategoryController::class);
        Route::resource('promotions', PromotionController::class);
    });

    // INVENTORY - Requires 'inventory' permission
    Route::middleware('permission:inventory')->group(function () {
        Route::resource('inventory-items', InventoryController::class);
        
        Route::get('/inventory', function () {
             return view('inventory.index');
        })->name('inventory.index');
    });

    // REPORTS - Requires 'reports' permission
    Route::middleware('permission:reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // KITCHEN/KDS - Requires 'kitchen' permission
    Route::middleware('permission:kitchen')->group(function () {
        Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::patch('/kitchen/{order}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.update-status');
    });

    // BILLING - Requires 'billing' permission
    Route::middleware('permission:billing')->group(function () {
        Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
        Route::post('/billing/{order}/payment', [BillingController::class, 'processPayment'])->name('billing.process-payment');
        Route::get('/billing/{order}/details', [BillingController::class, 'getOrderDetails'])->name('billing.order-details');
    });

    // SETTINGS - Requires 'settings' permission
    Route::middleware('permission:settings')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('staff', StaffController::class)->except(['create', 'edit', 'show']);

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__.'/auth.php';

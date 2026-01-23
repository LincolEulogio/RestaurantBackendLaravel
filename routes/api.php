<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes for frontend
Route::get('/products', [ProductController::class, 'apiIndex']);
Route::get('/categories', [CategoryController::class, 'apiIndex']);

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

// Public order creation (no auth required for customers)
Route::post('/orders', [OrderController::class, 'store']);

// Public reservation routes
Route::get('/reservations/availability', [\App\Http\Controllers\Api\ReservationController::class, 'checkAvailability']);
Route::get('/reservations/available-tables', [\App\Http\Controllers\Api\ReservationController::class, 'getAvailableTables']);
Route::get('/blogs', [BlogController::class, 'apiIndex']);
Route::get('/blogs/{slug}', [BlogController::class, 'apiShow']);
Route::get('/promotions', [\App\Http\Controllers\PromotionController::class, 'apiIndex']);
Route::post('/reservations', [\App\Http\Controllers\Api\ReservationController::class, 'store']);

// Protected routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

    // Waiter Routes
    Route::prefix('waiter')->group(function () {
        Route::get('/tables', [\App\Http\Controllers\Api\WaiterOrderController::class, 'tables']);
        Route::patch('/tables/{table}/status', [\App\Http\Controllers\Api\WaiterOrderController::class, 'updateTableStatus']);
        Route::post('/tables/{table}/start-session', [\App\Http\Controllers\Api\WaiterOrderController::class, 'startSession']);
        Route::post('/orders', [\App\Http\Controllers\Api\WaiterOrderController::class, 'storeOrder']);
        Route::get('/my-orders', [\App\Http\Controllers\Api\WaiterOrderController::class, 'myOrders']);
    });
});

// QR Self-Service Routes
Route::prefix('qr')->group(function () {
    Route::get('/table/{qrCode}', [\App\Http\Controllers\Api\QROrderController::class, 'checkTable']);
    Route::post('/orders', [\App\Http\Controllers\Api\QROrderController::class, 'storeOrder']);
    Route::post('/call-waiter', [\App\Http\Controllers\Api\QROrderController::class, 'callWaiter']);
    Route::post('/request-bill', [\App\Http\Controllers\Api\QROrderController::class, 'requestBill']);
});

// Culqi Payment Routes
Route::prefix('payment')->group(function () {
    Route::post('/process-card', [\App\Http\Controllers\Api\PaymentController::class, 'processCardPayment']);
    Route::post('/create-order', [\App\Http\Controllers\Api\PaymentController::class, 'createCulqiOrder']);
    Route::post('/webhook', [\App\Http\Controllers\Api\PaymentController::class, 'webhook']);
});

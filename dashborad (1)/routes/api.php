<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\BrandApiController;
use App\Http\Controllers\Api\BannerApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Enhanced API routes for the product management system
|
*/

// Public Product API Routes
Route::prefix('v1/products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index']);
    Route::get('/search', [ProductApiController::class, 'search']);
    Route::get('/stats', [ProductApiController::class, 'stats']);
    Route::get('/{product}', [ProductApiController::class, 'show']);
    Route::get('/{product}/analytics', [ProductApiController::class, 'analytics']);
});

// Public Category API Routes
Route::prefix('v1/categories')->group(function () {
    Route::get('/', [CategoryApiController::class, 'index']);
    Route::get('/search', [CategoryApiController::class, 'search']);
    Route::get('/stats', [CategoryApiController::class, 'stats']);
    Route::get('/tree', [CategoryApiController::class, 'tree']);
    Route::get('/{category}', [CategoryApiController::class, 'show']);
    Route::get('/{category}/analytics', [CategoryApiController::class, 'analytics']);
});

// Public Brand API Routes
Route::prefix('v1/brands')->group(function () {
    Route::get('/', [BrandApiController::class, 'index']);
    Route::get('/search', [BrandApiController::class, 'search']);
    Route::get('/stats', [BrandApiController::class, 'stats']);
    Route::get('/featured', [BrandApiController::class, 'featured']);
    Route::get('/{brand}', [BrandApiController::class, 'show']);
    Route::get('/{brand}/analytics', [BrandApiController::class, 'analytics']);
});

// Public Banner API Routes
Route::prefix('v1/banners')->group(function () {
    Route::get('/', [BannerApiController::class, 'index']);
    Route::get('/active', [BannerApiController::class, 'active']);
    Route::get('/stats', [BannerApiController::class, 'stats']);
    Route::get('/{banner}', [BannerApiController::class, 'show']);
    Route::post('/{banner}/track-impression', [BannerApiController::class, 'trackImpression']);
    Route::post('/{banner}/track-click', [BannerApiController::class, 'trackClick']);
});

// Protected Admin API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Protected Product Management
    Route::prefix('admin/products')->group(function () {
        Route::post('/', [ProductApiController::class, 'store']);
        Route::put('/{product}', [ProductApiController::class, 'update']);
        Route::delete('/{product}', [ProductApiController::class, 'destroy']);
        Route::patch('/{product}/toggle-status', [ProductApiController::class, 'toggleStatus']);
        Route::post('/bulk-operations', [ProductApiController::class, 'bulkOperations']);
    });

    // Protected Category Management
    Route::prefix('admin/categories')->group(function () {
        Route::post('/', [CategoryApiController::class, 'store']);
        Route::put('/{category}', [CategoryApiController::class, 'update']);
        Route::delete('/{category}', [CategoryApiController::class, 'destroy']);
        Route::patch('/{category}/toggle-status', [CategoryApiController::class, 'toggleStatus']);
        Route::post('/bulk-operations', [CategoryApiController::class, 'bulkOperations']);
    });

    // Protected Brand Management
    Route::prefix('admin/brands')->group(function () {
        Route::post('/', [BrandApiController::class, 'store']);
        Route::put('/{brand}', [BrandApiController::class, 'update']);
        Route::delete('/{brand}', [BrandApiController::class, 'destroy']);
        Route::patch('/{brand}/toggle-status', [BrandApiController::class, 'toggleStatus']);
        Route::post('/bulk-operations', [BrandApiController::class, 'bulkOperations']);
    });

    // Protected Banner Management
    Route::prefix('admin/banners')->group(function () {
        Route::post('/', [BannerApiController::class, 'store']);
        Route::put('/{banner}', [BannerApiController::class, 'update']);
        Route::delete('/{banner}', [BannerApiController::class, 'destroy']);
        Route::patch('/{banner}/toggle-status', [BannerApiController::class, 'toggleStatus']);
        Route::post('/bulk-operations', [BannerApiController::class, 'bulkOperations']);
    });

    // Analytics Routes
    Route::prefix('admin/analytics')->group(function () {
        Route::get('/dashboard', [ProductApiController::class, 'getDashboardAnalytics']);
    });

    // Email notification routes
    Route::post('/notifications/send-order-emails', [NotificationController::class, 'sendOrderEmails']);
});

// Rate limiting test route
Route::get('/test-rate-limit', function () {
    return response()->json([
        'message' => 'API is working',
        'timestamp' => now(),
        'request_count' => rand(1, 100)
    ]);
})->middleware(['api.rate.limit']);

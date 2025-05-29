<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\AdminUserController;
use App\Http\Controllers\API\AdminOrderController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::apiResource('products', ProductController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);

    // Wishlist Routes
    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist', [WishlistController::class, 'store']);
    Route::delete('wishlist/{product}', [WishlistController::class, 'destroy']);

    // Order Routes
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'store']);

    // Rating Routes
    Route::get('products/{product}/ratings', [RatingController::class, 'index']);
    Route::post('products/{product}/ratings', [RatingController::class, 'store']);
    Route::put('ratings/{rating}', [RatingController::class, 'update']);
    Route::delete('ratings/{rating}', [RatingController::class, 'destroy']);

    // Admin User Routes
    Route::apiResource('admin/users', AdminUserController::class);

    // Admin Order Routes
    Route::apiResource('admin/orders', AdminOrderController::class)->only(['index', 'show', 'update']);
}); 

<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/api/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{product}', [ProductController::class, 'show']);
});

Route::middleware('auth')->group(function () {
    Route::prefix('/api/orders')->group(function () {
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
    });

    Route::prefix('/api/cabinet')->group(function () {
        Route::get('/orders', [UserController::class, 'ordersHistory']);
        Route::put('/profile', [UserController::class, 'update']);
    });
});

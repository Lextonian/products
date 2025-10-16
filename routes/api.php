<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\PublicCatalogController;

// Аутентификация
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
});

// Админские эндпоинты
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('product_categories', ProductCategoryController::class);
});

// Публичные эндпоинты
Route::prefix('public')->group(function () {
    Route::get('products', [PublicCatalogController::class, 'products']);
    Route::get('products/{product:slug}', [PublicCatalogController::class, 'product']);
    Route::get('product_categories', [PublicCatalogController::class, 'categoriesTree']);
    Route::get('product_categories_with_products', [PublicCatalogController::class, 'categoriesWithProducts']);
});

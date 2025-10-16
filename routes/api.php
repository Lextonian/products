<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\PublicCatalogController;

// Админские эндпоинты
Route::prefix('admin')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::apiResource('product_categories', ProductCategoryController::class);
});

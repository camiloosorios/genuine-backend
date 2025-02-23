<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FulfillmentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/products', ProductController::class);

Route::post('/fulfillment/products', [FulfillmentController::class, 'getProductsIntent']);
Route::post('/fulfillment/products/count', [FulfillmentController::class, 'getProductsCountIntent']);
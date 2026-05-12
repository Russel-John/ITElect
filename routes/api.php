<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductTransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);

Route::post('products/{product}/stock-in', [ProductTransactionController::class, 'stockIn']);
Route::post('products/{product}/stock-out', [ProductTransactionController::class, 'stockOut']);
Route::get('transactions', [ProductTransactionController::class, 'index']);

<?php

use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('products', ProductsController::class)->only(['index', 'store']);
Route::apiResource('inventory', InventoryController::class)->only(['index', 'store']);
Route::apiResource('flash-sales', FlashSaleController::class)->only(['index', 'store']);
Route::apiResource('orders', OrdersController::class)->only(['store']);

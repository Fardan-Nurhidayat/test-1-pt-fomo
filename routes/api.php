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

Route::apiResource('v1/products', ProductsController::class);
Route::apiResource('v1/inventory', InventoryController::class);
Route::apiResource('v1/flash-sales', FlashSaleController::class);
Route::apiResource('v1/orders', OrdersController::class);

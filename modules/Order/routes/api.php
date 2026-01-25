<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\CartController;
use Modules\Order\Http\Controllers\OrderController;

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/items', [CartController::class, 'add']);

Route::post('/orders/checkout', [OrderController::class, 'checkout']);

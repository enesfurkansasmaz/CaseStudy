<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\DiscountRuleController;

Route::apiResource('discount-rules', DiscountRuleController::class);


Route::apiResource('products', ProductController::class);

Route::get('/orders', [OrderController::class, 'listOrder']);
Route::post('/orders', [OrderController::class, 'addOrder']);
Route::delete('/orders/{order}', [OrderController::class, 'deleteOrder']);

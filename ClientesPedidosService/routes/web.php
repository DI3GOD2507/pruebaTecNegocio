<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Customer\CustomerController;

use App\Http\Controllers\Api\Order\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/', [OrderController::class, 'store']);
    Route::put('/{id}', [OrderController::class, 'update']);
    Route::delete('/{id}', [OrderController::class, 'destroy']);
});

Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::get('/{id}', [CustomerController::class, 'show']);
    Route::post('/', [CustomerController::class, 'store']);
    Route::put('/{id}', [CustomerController::class, 'update']);
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
});




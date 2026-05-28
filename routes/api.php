<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuotationController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me'])
            ->name('me');

        Route::post('/quotation', [QuotationController::class, 'store'])
            ->name('quotation.store');
    });
});

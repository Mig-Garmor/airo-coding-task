<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuotationController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {

    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/quotation', [QuotationController::class, 'store'])
        ->middleware('auth:api');
});

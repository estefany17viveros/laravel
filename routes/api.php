<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('veterinarians', VeterinarianController::class);

Route::apiResource('payment-methods', PaymentMethodController::class);

Route::apiResource('forums', ForumController::class);
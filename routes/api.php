<?php

use App\Http\Controllers\API\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('credit', \App\Http\Controllers\API\Credit_DebitController::class);
    Route::get('/history/{client_id}/{from}/{to}', [\App\Http\Controllers\API\CalculationController::class, 'history']);
    Route::get('/debt/{from}/{to}', [\App\Http\Controllers\API\CalculationController::class, 'clientDebt']);
    Route::get('/debt/{from}/{to}', [\App\Http\Controllers\API\CalculationController::class, 'clientDebt']);
});

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

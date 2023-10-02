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
    Route::apiResource('store', \App\Http\Controllers\API\StoreController::class);
    Route::post('/delete/data/{id}', [\App\Http\Controllers\API\Credit_DebitController::class, 'delete']);
    Route::get('/aktSverki/{client_id}/{from}/{to}', [\App\Http\Controllers\API\CalculationController::class, 'aktSverki']);
    Route::get('/debt/{from}/{to}', [\App\Http\Controllers\API\CalculationController::class, 'clientDebt']);
    Route::get('/calculate', [\App\Http\Controllers\API\CalculationController::class, 'calculate']);
    Route::get('/pdf', [\App\Http\Controllers\API\CalculationController::class, 'pdf']);
    Route::get('/store/history/{id}', [\App\Http\Controllers\API\CalculationController::class, 'storeHistory']);
    Route::get('/getClientInfo/{id}', [ClientController::class, 'getClientInfo']);
    Route::get('/history/{client_id}/{from}/{to}', [\App\Http\Controllers\API\HistoryController::class, 'history']);
});

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

<?php

use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\StoreController;
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
    Route::get('clientHistory/{id}', [ClientController::class, 'clientHistory']);
    Route::get('todayHistory/{id}', [ClientController::class, 'todayHistory']);
    Route::get('getFiveClients', [ClientController::class, 'getFiveClients']);
    Route::get('searchClient/{client}', [ClientController::class, 'searchClient']);
    Route::get('allDebitCreditOfClient/{id}', [ClientController::class, 'allDebitCreditOfClient']);

    Route::apiResource('credit', \App\Http\Controllers\API\Credit_DebitController::class);

    Route::apiResource('store', \App\Http\Controllers\API\StoreController::class);
    Route::get('getFiveStores', [\App\Http\Controllers\API\StoreController::class, 'getFiveStores']);
    Route::get('searchStore/{store}', [StoreController::class, 'searchStore']);

    Route::get('/credit/edit/{id}', [\App\Http\Controllers\API\Credit_DebitController::class, 'edit']);
    Route::post('/delete/data/', [\App\Http\Controllers\API\Credit_DebitController::class, 'delete']);

    Route::get('/aktSverki/{client_id}/{from}/{to}', [\App\Http\Controllers\API\CalculationController::class, 'aktSverki']);
    Route::get('/debt/{from}/{to}', [\App\Http\Controllers\API\CalculationController::class, 'clientDebt']);
    Route::get('/calculate', [\App\Http\Controllers\API\CalculationController::class, 'calculate']);
    Route::get('/pdf', [\App\Http\Controllers\API\CalculationController::class, 'pdf']);
    Route::get('/store/history/{id}', [\App\Http\Controllers\API\CalculationController::class, 'storeHistory']);
    Route::get('/getClientInfo/{id}', [ClientController::class, 'getClientInfo']);

    Route::get('/history/{client_id}/{from}/{to}', [\App\Http\Controllers\API\HistoryController::class, 'history']);
    Route::get('/check', [\App\Http\Controllers\API\CheckController::class, 'check']);
    Route::get('/connect', [\App\Http\Controllers\API\CheckController::class, 'connect']);

    Route::get('user', [\App\Http\Controllers\API\UserController::class, 'index']);
    Route::post('user', [\App\Http\Controllers\API\UserController::class, 'store']);
    Route::put('user/{id}', [\App\Http\Controllers\API\UserController::class, 'update']);
    Route::delete('user/{id}', [\App\Http\Controllers\API\UserController::class, 'destroy']);
});

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);

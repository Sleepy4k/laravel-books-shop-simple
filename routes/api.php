<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/', function() {
    return response()->json([
        'author' => 'Benjamin4k',
        'pawang' => 'Sayang Zooane'
    ]);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);
    
    // User Route
    Route::prefix('user')->group(function() {
        Route::apiResource('account', AccountController::class, ['only' => ['index']]);
        Route::apiResource('transaction', TransactionController::class, ['only' => ['index','show']]);
    });

    // Admin Route
    Route::prefix('admin')->group(function() {
        Route::apiResource('account', AccountController::class);
        Route::apiResource('transaction', TransactionController::class);
    });
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\PaymentController;
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
        Route::get('account', [AccountController::class, 'index']);
        Route::post('payment', [PaymentController::class, 'store']);
        Route::apiResource('book', BookController::class, ['only' => ['index','show']]);
        Route::apiResource('transaction', TransactionController::class, ['only' => ['index','show']]);
    });

    // Admin Route
    Route::prefix('admin')->group(function() {
        Route::apiResource('book', BookController::class);
        Route::apiResource('account', AccountController::class);
        Route::apiResource('transaction', TransactionController::class);
    });
});
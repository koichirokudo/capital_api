<?php

use App\Http\Controllers\FinancialTransactionRatioController;
use App\Http\Controllers\FinancialTransactionsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CapitalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UserGroupsController;
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
Route::post('/login', LoginController::class);
Route::post('/users', [UsersController::class, 'create']);
Route::post('/users/verify', [UsersController::class, 'verify']);

Route::middleware(['auth:sanctum'])->group(static function () {
    Route::get('/me', MeController::class);
    Route::get('/user', [UsersController::class, 'index']);
    Route::get('/user/group', [UserGroupsController::class, 'group']);
    Route::post('/logout', LogoutController::class);
    Route::get('/financial-transactions', [FinancialTransactionsController::class, 'index']);
    Route::get('/financial-transaction-ratio', [FinancialTransactionRatioController::class, 'index']);
    Route::post('/financial-transaction-ratio/{id}', [FinancialTransactionRatioController::class, 'update']);
    Route::get('/capitals', [CapitalController::class, 'index']);
    Route::post('/capitals', [CapitalController::class, 'create']);
    Route::get('/capitals/calculate/{year}/{month}', [CapitalController::class, 'calculate']);
    Route::patch('/capitals/{id}', [CapitalController::class, 'update']);
    Route::delete('/capitals/{id}', [CapitalController::class, 'destroy']);
    Route::get('/year', [ReportController::class, 'getYearlyReport']);
    Route::get('/month', [ReportController::class, 'getMonthlyReport']);
});

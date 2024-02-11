<?php

use App\Http\Controllers\ExpensesItemsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CapitalController;
use App\Http\Controllers\ReportController;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', MeController::class);
    Route::post('/logout', LogoutController::class);
    Route::get('/expenses-items', [ExpensesItemsController::class, 'index']);
    Route::get('/capitals', [CapitalController::class, 'index']);
    Route::post('/capitals', [CapitalController::class, 'create']);
    Route::patch('/capitals/{id}', [CapitalController::class, 'update']);
    Route::delete('/capitals/{id}', [CapitalController::class, 'destroy']);
    Route::get('/year', [ReportController::class, 'getYearlyReport']);
    Route::get('/month', [ReportController::class, 'getMonthlyReport']);
});

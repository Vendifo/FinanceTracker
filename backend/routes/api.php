<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FinanceController;

use Illuminate\Support\Facades\Route;

// Публичные маршруты (не требуют токена)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Защищённые маршруты (требуют токен)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/current', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Смена пароля конкретного пользователя (для админа)
    Route::post('users/{user}/change-password', [UserController::class, 'changePassword']);



    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('articles', ArticleController::class);
    Route::apiResource('offices', OfficeController::class);
    Route::apiResource('incomes', IncomeController::class);
    Route::apiResource('expenses', ExpenseController::class);

    Route::get('finance', [FinanceController::class, 'index']);


});


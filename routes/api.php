<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\UserController;
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

Route::prefix('admin')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('me', 'me');
        Route::post('logout', 'logout');
    });

    Route::middleware(['auth:api'])
        ->controller(UserController::class)
        ->group(function () {
            Route::get('user', 'user');
            Route::get('users/all', 'all');
        });
    Route::apiResource('users', UserController::class);
});

<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\UserController;

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
            Route::apiResource('users', UserController::class);
        });
});


Route::prefix('redis')->group(function () {
    Route::post('users/cache', function () {
        $users = User::latest()->get();

        $array = [];
        foreach ($users as $user) {
            $array["user:$user->id"] = $user;
        }

        Redis::rpush('users', $array);

        return response()->json("Redis Cache");
    });
    Route::post('users', function (Request $request) {
        $user = User::create($request->all());
        Redis::lpush('users',  $user);

        return response()->json($user);
    });
    Route::get('users', function () {
        $users = Redis::lrange('users', 0, -1);
        // $users = Redis::get('users');
        return response()->json($users);
    });
    Route::delete('user/{user}', function (User $user) {
        return Redis::lrem('users', 0, "user:$user->id");
    });
    Route::post('flush', function () {
        // Cache::store('redis')->flush();
        Redis::del('users');
        return response()->json("Redis flush");
    });
});

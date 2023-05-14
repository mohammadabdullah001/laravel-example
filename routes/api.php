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
        Route::get('clients', 'clients');
        Route::post('login', 'login');
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



// Route::post('/token', [
//     'uses' => 'AccessTokenController@issueToken',
//     'as' => 'token',
//     'middleware' => 'throttle',
// ]);

// Route::get('/authorize', [
//     'uses' => 'AuthorizationController@authorize',
//     'as' => 'authorizations.authorize',
//     'middleware' => 'web',
// ]);

// $guard = config('passport.guard', null);

// Route::middleware(['web', $guard ? 'auth:' . $guard : 'auth'])->group(function () {
//     Route::post('/token/refresh', [
//         'uses' => 'TransientTokenController@refresh',
//         'as' => 'token.refresh',
//     ]);

//     Route::post('/authorize', [
//         'uses' => 'ApproveAuthorizationController@approve',
//         'as' => 'authorizations.approve',
//     ]);

//     Route::delete('/authorize', [
//         'uses' => 'DenyAuthorizationController@deny',
//         'as' => 'authorizations.deny',
//     ]);

//     Route::get('/tokens', [
//         'uses' => 'AuthorizedAccessTokenController@forUser',
//         'as' => 'tokens.index',
//     ]);

//     Route::delete('/tokens/{token_id}', [
//         'uses' => 'AuthorizedAccessTokenController@destroy',
//         'as' => 'tokens.destroy',
//     ]);

//     Route::get('/clients', [
//         'uses' => 'ClientController@forUser',
//         'as' => 'clients.index',
//     ]);

//     Route::post('/clients', [
//         'uses' => 'ClientController@store',
//         'as' => 'clients.store',
//     ]);

//     Route::put('/clients/{client_id}', [
//         'uses' => 'ClientController@update',
//         'as' => 'clients.update',
//     ]);

//     Route::delete('/clients/{client_id}', [
//         'uses' => 'ClientController@destroy',
//         'as' => 'clients.destroy',
//     ]);

//     Route::get('/scopes', [
//         'uses' => 'ScopeController@all',
//         'as' => 'scopes.index',
//     ]);

//     Route::get('/personal-access-tokens', [
//         'uses' => 'PersonalAccessTokenController@forUser',
//         'as' => 'personal.tokens.index',
//     ]);

//     Route::post('/personal-access-tokens', [
//         'uses' => 'PersonalAccessTokenController@store',
//         'as' => 'personal.tokens.store',
//     ]);

//     Route::delete('/personal-access-tokens/{token_id}', [
//         'uses' => 'PersonalAccessTokenController@destroy',
//         'as' => 'personal.tokens.destroy',
//     ]);
// });

<?php


use Laravel\Octane\Facades\Octane;


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

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Octane::route('GET', '/faster', function () {
    return new Response('Hello from Octane.');
});

Route::get('response-per-minute', function () {
    return response()->json([
        'data' => 'ok'
    ]);
});

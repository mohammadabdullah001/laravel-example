<?php

use Laravel\Octane\Facades\Octane;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Octane::route('GET', '/faster', function () {
    return new Response('Hello from Octane.');
});

Route::get('/', function () {
    return view('welcome');
});

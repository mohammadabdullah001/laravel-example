<?php

use Spatie\Async\Pool;
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

Route::get('/test', function () {
    return response()->json([
        'data' => 'ok'
    ]);
});


Route::get('/async-task', function () {
    $pool = Pool::create();

    $count = 0;

    $pool[] = async(function () {
        sleep(1);

        return 1;
    })->then(function (int $output) use (&$count) {
        $count += $output;
    });

    $pool[] = async(function () {
        sleep(5);

        return 5;
    })->then(function (int $output) use (&$count) {
        $count += $output;
    });

    info('before', [$count]);
    await($pool);
    info('after', [$count]);

    return response()->json($count);
});

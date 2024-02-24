<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Action\AddNumberAction;
use App\Jobs\SortUrl\SortUrlJob;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\UserController;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

Route::get('/error', function () {


    User::query()
        ->find(500)
        ->delete();

    // try {
    //     User::query()
    //         ->find(500)
    //         ->delete();
    // } catch (Throwable $caught) {
    //     report($caught); // ignored
    // }
});

Route::get('/test', function () {

    dd(app());

    $addNumber = resolve(AddNumberAction::class);
    // $addNumber = app(AddNumberAction::class);
    $result = $addNumber->run(5);
    dd($result);
});

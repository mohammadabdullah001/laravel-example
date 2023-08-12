<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SortUrl\SortUrlJob;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;
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

Route::get('response-per-minute', function () {
    return response()->json([
        'data' => 'ok'
    ]);
});

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

Route::prefix('cache')->group(function () {
    Route::post('put', function (Request $request) {
        $name = $request->input('name');

        $user = user::where(['name' => $name])->first();

        $userData = [
            'id' => @$user->id,
            'name' => @$user->name,
        ];

        $isCache =  Cache::put('user:' . $name, $userData, $seconds = null);

        if ($isCache) {
            $data = "Cache Yes";
        } else {
            $data = "Cache No";
        }

        return response()->json($data);
    });
    Route::post('add', function (Request $request) {
        $name = $request->input('name');

        $user = user::where(['name' => $name])->first();

        $userData = [
            'id' => @$user->id,
            'name' => @$user->name,
        ];

        $isCache =  Cache::add('user:' . $name, $userData, $seconds = null);


        if ($isCache) {
            $data = "Cache Yes";
        } else {
            $data = "Cache No";
        }

        return response()->json($data);
    });

    Route::post('forever', function (Request $request) {
        $name = $request->input('name');

        $user = user::where(['name' => $name])->first();

        $userData = [
            'id' => @$user->id,
            'name' => @$user->name,
        ];

        Cache::forever('user:' . $name, $userData);

        return response()->json('Cache forever');
    });

    Route::get('remember', function (Request $request) {
        $name = $request->query('name');
        $user =  Cache::remember('user:' . $name, $seconds = null, function () use ($name) {
            $user =  user::where([
                'name' => $name
            ])->first();

            return [
                'id' => @$user->id,
                'name' => @$user->name,
            ];
        });

        return response()->json($user);
    });

    Route::get('has', function (Request $request) {
        $name = $request->query('name');

        if (Cache::has('user:' . $name)) {
            $data = "Yes";
        } else {
            $data = "No";
        }

        return response()->json($data);
    });
    Route::get('get', function (Request $request) {
        $name = $request->query('name');

        $data = Cache::get('user:' . $name);

        return response()->json($data);
    });

    Route::delete('forget/{key}', function ($key) {
        Cache::forget('user:' . $key);

        return response()->json("Cache delete: " . $key);
    });

    Route::post('flush', function () {
        Cache::flush();

        return response()->json("Cache flush");
    });
});


Route::prefix('database-cache')->group(function () {
    Route::post('lock', function (Request $request) {
        $name = $request->input('name');


        Cache::store('database')->lock('processing', 10)->block(5, function () use ($name) {
            return $name;
        });

        return "ok";
    });
    Route::post('forever', function (Request $request) {
        $name = $request->input('name');

        $user = user::where(['name' => $name])->first();

        $userData = [
            'id' => @$user->id,
            'name' => @$user->name,
        ];

        return Cache::store('database')->forever('user:' . $name, $userData);
    });

    Route::post('put', function (Request $request) {
        $name = $request->input('name');

        $user = user::where(['name' => $name])->first();

        $userData = [
            'id' => @$user->id,
            'name' => @$user->name,
        ];

        $isCache =  Cache::store('database')->put('user:' . $name, $userData, $seconds = null);

        if ($isCache) {
            $data = "Cache Yes";
        } else {
            $data = "Cache No";
        }

        return response()->json($data);
    });
    Route::post('add', function (Request $request) {
        $name = $request->input('name');

        $user = user::where(['name' => $name])->first();

        $userData = [
            'id' => @$user->id,
            'name' => @$user->name,
        ];

        $isCache =  Cache::store('database')->add('user:' . $name, $userData, $seconds = null);


        if ($isCache) {
            $data = "Cache Yes";
        } else {
            $data = "Cache No";
        }

        return response()->json($data);
    });

    Route::post('forever', function (Request $request) {
        $name = $request->input('name');

        $user = user::where(['name' => $name])->first();

        $userData = [
            'id' => @$user->id,
            'name' => @$user->name,
        ];

        Cache::store('database')->forever('user:' . $name, $userData);

        return response()->json('Cache forever');
    });

    Route::get('remember', function (Request $request) {
        $name = $request->query('name');
        $user =  Cache::store('database')->remember('user:' . $name, $seconds = null, function () use ($name) {
            $user =  user::where([
                'name' => $name
            ])->first();

            return [
                'id' => @$user->id,
                'name' => @$user->name,
            ];
        });

        return response()->json($user);
    });

    Route::get('has', function (Request $request) {
        $name = $request->query('name');

        if (Cache::store('database')->has('user:' . $name)) {
            $data = "Yes";
        } else {
            $data = "No";
        }

        return response()->json($data);
    });
    Route::get('get', function (Request $request) {
        $name = $request->query('name');

        $data = Cache::store('database')->get('user:' . $name);

        return response()->json($data);
    });

    Route::delete('forget/{key}', function ($key) {
        Cache::store('database')->forget('user:' . $key);

        return response()->json("Cache delete: " . $key);
    });

    Route::post('flush', function () {
        Cache::store('database')->flush();

        return response()->json("Cache flush");
    });
});


Route::prefix('sort-url')->group(function () {
    Route::get('/', function (Request $request) {
        $name = $request->query('name');

        $user =  Cache::store('database_examples')->rememberForever('user:' . $name, function () use ($name) {
            $user =  user::where([
                'name' => $name
            ])->first();

            return [
                'id' => @$user->id,
                'name' => @$user->name,
            ];
        });


        // Benchmark::dd([
        //     'Scenario 1' => fn () => SortUrlJob::dispatch($user),
        //     'Scenario 2' => fn () => SortUrlJob::dispatchAfterResponse($user),
        // ]);

        return redirect('https://www.facebook.com');
    });

    Route::get('/visitors', function (Request $request) {

        $fromDate = Carbon::make($request->query('fromDate'))->format('Y-m-d');
        $toDate = Carbon::make($request->query('toDate'))->format('Y-m-d');

        return ShortUrl::query()
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                $query->with([
                    'VisitorCountries' => function ($q) use ($fromDate, $toDate) {
                        $q->select('short_url_id', 'country')
                            ->selectRaw('SUM(total_count) as total_count')
                            ->whereBetween('visit_at', [$fromDate, $toDate])
                            ->groupBy('short_url_id', 'country', 'total_count')
                            ->orderByDesc('total_count')
                            ->limit(5);
                    },
                    'VisitorCities' => function ($q) use ($fromDate, $toDate) {
                        $q->select('short_url_id', 'city')
                            ->selectRaw('SUM(total_count) as total_count')
                            ->whereBetween('visit_at', [$fromDate, $toDate])
                            ->groupBy('short_url_id', 'city', 'total_count')
                            ->orderByDesc('total_count')
                            ->limit(5);
                    },
                ]);
            })
            ->paginate(10);
    });
});

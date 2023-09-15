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
use Illuminate\Database\Query\JoinClause;
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


Route::prefix('sort-urls')->group(function () {
    Route::get('/redirect', function (Request $request) {
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

    Route::get('/eloquent', function (Request $request) {

        $campaign_id =  (int)data_get($request->all(), 'campaign_id',-1);

        return ShortUrl::query()
        ->withCount([
            'VisitorCounts as visitor_count' => function ($q) {
                $q->selectRaw('SUM(total_count) as total_count');
            },
        ])
            ->with([
                'campaign',
                'VisitorCountries' => function ($q) {
                    $q->select('short_url_id', 'country')
                        ->selectRaw('SUM(total_count) as total_count')
                        ->groupBy('short_url_id', 'country', 'total_count')
                        ->orderByDesc('total_count')
                        ->limit(5);
                },
                'VisitorCities' => function ($q) {
                    $q->select('short_url_id', 'city')
                        ->selectRaw('SUM(total_count) as total_count')
                        ->groupBy('short_url_id', 'city', 'total_count')
                        ->orderByDesc('total_count')
                        ->limit(5);
                },
            ])
            ->when($campaign_id != -1, function ($q) use ($campaign_id) {
                $q->where('campaign_id', $campaign_id);
            })
            ->orderBy('visitor_count','desc')
            ->paginate(25);
    });

    Route::get('/builder', function (Request $request) {

        // $fromDate = Carbon::make($request->query('fromDate'))->format('Y-m-d');
        // $toDate = Carbon::make($request->query('toDate'))->format('Y-m-d');

        // return ShortUrl::query()
        //     ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
        //         $query->with([
        //             'VisitorCountries' => function ($q) use ($fromDate, $toDate) {
        //                 $q->select('short_url_id', 'country')
        //                     ->selectRaw('SUM(total_count) as total_count')
        //                     ->whereBetween('visit_at', [$fromDate, $toDate])
        //                     ->groupBy('short_url_id', 'country', 'total_count')
        //                     ->orderByDesc('total_count')
        //                     ->limit(5);
        //             },
        //             'VisitorCities' => function ($q) use ($fromDate, $toDate) {
        //                 $q->select('short_url_id', 'city')
        //                     ->selectRaw('SUM(total_count) as total_count')
        //                     ->whereBetween('visit_at', [$fromDate, $toDate])
        //                     ->groupBy('short_url_id', 'city', 'total_count')
        //                     ->orderByDesc('total_count')
        //                     ->limit(5);
        //             },
        //         ]);
        //     })
        //     ->paginate(10);





        return ShortUrl::query()
            ->with([
                'VisitorCountries' => function ($q) {
                    $q->select('short_url_id', 'country')
                        ->selectRaw('SUM(total_count) as total_count')
                        ->groupBy('short_url_id', 'country', 'total_count')
                        ->orderByDesc('total_count')
                        ->limit(5);
                },
                'VisitorCities' => function ($q) {
                    $q->select('short_url_id', 'city')
                        ->selectRaw('SUM(total_count) as total_count')
                        ->groupBy('short_url_id', 'city', 'total_count')
                        ->orderByDesc('total_count')
                        ->limit(5);
                },
            ])

            ->get();



        // $visitorCountries = DB::table('visitor_countries')
        //     ->select([
        //         'short_url_id',
        //         'country',
        //         DB::raw('SUM(total_count) as total_count')
        //     ])
        //     ->groupBy('short_url_id', 'country')
        //     ->orderByDesc('total_count');

        // $originalData = DB::table('short_urls')
        //     ->joinSub($visitorCountries, 'visitor_countries', function ($join) {
        //         $join->on('short_urls.id', '=', 'visitor_countries.short_url_id');
        //     })
        //     ->select('short_urls.*', 'visitor_countries.*')
        //     ->orderByDesc('short_urls.id')
        //     ->orderByDesc('visitor_countries.total_count')
        //     ->get();


        // $transformedData = [];
        // collect($originalData)
        //     ->map(function ($item) use (&$transformedData) {


        //         $id = $item->id;
        //         $domain = $item->domain;

        //         // Check if the id-domain combination already exists in transformedData
        //         $existingItemKey = null;
        //         foreach ($transformedData as $key => $existing) {
        //             if ($existing['id'] == $id && $existing['domain'] == $domain) {
        //                 $existingItemKey = $key;
        //                 break;
        //             }
        //         }

        //         // If it doesn't exist, create a new entry; otherwise, add the country data to the existing entry
        //         if ($existingItemKey === null) {
        //             $transformedItem = [
        //                 'id' => $item->id,
        //                 'domain' => $item->domain,
        //                 'created_at' => $item->created_at,
        //                 'updated_at' => $item->updated_at,
        //                 'countries' => [
        //                     [
        //                         'short_url_id' => $item->short_url_id,
        //                         'country' => $item->country,
        //                         'total_count' => $item->total_count,
        //                     ],
        //                 ],
        //             ];
        //             $transformedData[] = $transformedItem;
        //         } else {
        //             if (count($transformedData[$existingItemKey]['countries']) <= 4) {
        //                 $transformedData[$existingItemKey]['countries'][] = [
        //                     'short_url_id' => $item->short_url_id,
        //                     'country' => $item->country,
        //                     'total_count' => $item->total_count,
        //                 ];
        //             }
        //         }

        //         return $transformedData;
        //     })
        //     ->values()
        //     ->all();

        // return  $transformedData;
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Query\JoinClause;


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


Route::prefix('sort-urls')->group(function () {

    Route::get('/builder', function (Request $request) {
        $request_all = $request->all();
        // $perPage = data_get($request_all, 'perPage', 10);
        $orderBy = data_get($request_all, 'orderBy', 'id');
        $orderDirection = data_get($request_all, 'orderDirection', 'asc');
        $filter_start_date = data_get($request_all, 'start_date', '1970-01-01');
        $filter_end_date = data_get($request_all, 'start_date', '2025-01-01');


        $subquery = DB::table('short_urls')
            ->leftJoin('campaigns', 'short_urls.campaign_id', '=', 'campaigns.id')
            ->leftJoin('visitor_counts', function ($join) use ($filter_start_date, $filter_end_date) {
                $join->on('short_urls.id', '=', 'visitor_counts.short_url_id')
                    ->whereBetween('visitor_counts.visit_at', [$filter_start_date, $filter_end_date]);
            })
            ->leftJoin('visitor_countries', function ($join) use ($filter_start_date, $filter_end_date) {
                $join->on('short_urls.id', '=', 'visitor_countries.short_url_id')
                    ->whereBetween('visitor_countries.visit_at', [$filter_start_date, $filter_end_date]);
            })
            ->select([
                'short_urls.id',
                'short_urls.domain',
                'campaigns.name as campaign_name',
                DB::raw('SUM(visitor_counts.total_count) as total_visitor_count'),
                'visitor_countries.country as country',
                DB::raw('SUM(visitor_countries.total_count) as total_country_visitor_count'),
                DB::raw('ROW_NUMBER() OVER (PARTITION BY short_urls.id ORDER BY SUM(visitor_countries.total_count) DESC) as country_rank')
            ])
            ->groupBy(['short_urls.id', 'visitor_countries.country']);

        return DB::table(DB::raw("({$subquery->toSql()}) as sub"))
            ->mergeBindings($subquery)
            ->where('country_rank', '<=', 5)
            ->orderBy($orderBy, $orderDirection)
            ->orderBy('total_country_visitor_count', 'DESC')
            ->get();
    });
});

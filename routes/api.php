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

Route::get('/collection', function () {

    $excel_questions = collect([
        [
            'title' => 'Single Question',
            'group_name' => 'Group 1',
            'sub_group_name' => null,
        ],
        [
            'title' => 'Multiple Question',
            'group_name' => 'Group 1',
            'sub_group_name' => null,
        ],
        [
            'title' => 'Long Question',
            'group_name' => 'Group 1',
            'sub_group_name' => 'Group 1 -> Sub Group 1',
        ],
        [
            'title' => 'Sigle Date Question',
            'group_name' => 'Group 1',
            'sub_group_name' => 'Group 1 -> Sub Group 1',
        ],
        [
            'title' => 'Single Question',
            'group_name' => 'Group 2',
            'sub_group_name' => null,
        ],
        [
            'title' => 'Long Question',
            'group_name' => 'Group 2',
            'sub_group_name' => 'Group 2 -> Sub Group 1',
        ],
        [
            'title' => 'Number Input Question',
            'group_name' => 'Group 2',
            'sub_group_name' => 'Group 2 -> Sub Group 2',
        ],
        [
            'title' => 'Date Range Question',
            'group_name' => 'Group 2',
            'sub_group_name' => 'Group 2 -> Sub Group 2',
        ],
    ]);

    $questionsGroups = collect([]);

    foreach ($excel_questions as $excel_question) {
        if (isset($excel_question['title']) && isset($excel_question['group_name'])) {

            if ($questionsGroups->count() > 0) {
                $questionsGroups->whenNotEmpty(function ($groups) use ($excel_question) {
                    if (isset($excel_question['sub_group_name'])) {
                        $searchedGroup =  $groups->first(function ($group) use ($excel_question) {
                            if ($group['name'] === $excel_question['group_name']) {
                                return $group;
                            }
                        });

                        if ($searchedGroup) {

                            $searchedSubGroup =  $searchedGroup['sub_groups']->first(function ($subGroup) use ($excel_question) {
                                if ($subGroup['name'] === $excel_question['sub_group_name']) {
                                    return $subGroup;
                                }
                            });

                            if ($searchedSubGroup) {
                                $searchedSubGroup['questions']->push(
                                    [
                                        'title' => $excel_question['title']
                                    ]
                                );
                            } else {
                                $searchedGroup['sub_groups']->push(collect([
                                    'name' => $excel_question['sub_group_name'],
                                    'questions' => collect([
                                        [
                                            'title' => $excel_question['title']
                                        ],
                                    ]),
                                ]));
                            }
                        } else {
                            $groups->push(collect([
                                'name' => $excel_question['group_name'],
                                'questions' => collect([]),
                                'sub_groups' => collect([
                                    [
                                        'name' => $excel_question['sub_group_name'],
                                        'questions' => collect([
                                            [
                                                'title' => $excel_question['title']
                                            ],
                                        ]),
                                    ],
                                ]),
                            ]));
                        }
                    } else {
                        $searchedGroup =  $groups->first(function ($group) use ($excel_question) {
                            if ($group['name'] === $excel_question['group_name']) {
                                return $group;
                            }
                        });

                        if ($searchedGroup) {
                            $searchedGroup['questions']->push(
                                [
                                    'title' => $excel_question['title']
                                ]
                            );
                        } else {
                            $groups->push(collect([
                                'name' => $excel_question['group_name'],
                                'questions' => collect([
                                    [
                                        'title' => $excel_question['title']
                                    ],
                                ]),
                                'sub_groups' => collect([]),
                            ]));
                        }
                    }
                });
            } else {
                $questionsGroups->whenEmpty(function ($groups) use ($excel_question) {
                    if (isset($excel_question['sub_group_name'])) {
                        $groups->push(collect([
                            'name' => $excel_question['group_name'],
                            'questions' => collect([]),
                            'sub_groups' => collect([
                                [
                                    'name' => $excel_question['sub_group_name'],
                                    'questions' => collect([
                                        [
                                            'title' => $excel_question['title']
                                        ],
                                    ]),
                                ],
                            ]),
                        ]));
                    } else {
                        $groups->push(collect([
                            'name' => $excel_question['group_name'],
                            'questions' => collect([
                                [
                                    'title' => $excel_question['title']
                                ],
                            ]),
                            'sub_groups' => collect([]),
                        ]));
                    }
                });
            }
        }
    }

    return response()->json($questionsGroups->values()->toArray());
});

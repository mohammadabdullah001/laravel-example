<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // $this->reportable(function (HttpException $e) {
        //     //
        // });

        $this->renderable(function (HttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            }
        });

        $this->renderable(function (Throwable $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getCode());
            }
        });
    }
}

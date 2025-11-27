<?php

use App\Exceptions\API\NotFoundException;
use App\Exceptions\API\ServerException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            //
        ]);

        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, $request) {
            if ($exception instanceof NotFoundException || $exception instanceof ServerException) {
                return $exception->render();
            }

            if ($exception instanceof ThrottleRequestsException) {
                return response()->json([
                    'success' => false,
                    'error'   => [
                        'code'                => 'TOO_MANY_REQUESTS',
                        'message'             => 'You are making requests too quickly. Please try again later.',
                        'retry_after_seconds' => $exception->getHeaders()['Retry-After'] ?? null,
                    ],
                ], 429);
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'error'   => [
                        'code'    => 'VALIDATION_ERROR',
                        'message' => 'Validation failed',
                        'details' => $exception->errors(),
                    ],
                ], 422);
            }

            return response()->json([
                'success' => false,
                'error'   => [
                    'code'    => 'UNEXPECTED_ERROR',
                    'message' => 'Something went wrong.',
                    'details' => config('app.debug') ? $exception->getMessage() : null,
                ],
            ], 500);
        });
    })
    ->create();

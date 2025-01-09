<?php

use \App\Http\Middleware\AdminMiddleware;
use \App\Http\Middleware\ForceJsonParsing;
use \App\Http\Middleware\LogActivities;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,


            // 'throttle:api',
            // \Illuminate\Routing\Middleware\SubstituteBindings::class,

            // Other middleware for parsing request content
            // ForceJsonParsing::class,

            // Log laravel Activities
            LogActivities::class,

        ]);

        $middleware->alias([

            'role' => \App\Http\Middleware\UserRoleMiddleware::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'admin' => AdminMiddleware::class,
            
        ]);




        //Incoming requests from your SPA can authenticate using Laravel's session cookies
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Start of render customized error message
        $exceptions->render(function (Throwable $e, Request $request) {

            Log::info('Request:', $request->all() ?? $request->getContent());
            Log::info('Raw Input: ' . $request->getContent());
            Log::error('Error:', [$e?->getMessage(), $e?->getTraceAsString()]);

            if ($request->is('api/*')) {

                // Custom response for all exceptions
                $response = [
                    'success' => false,
                    'message' => 'An error occurred. Please try again later.',
                    // Avoid exposing error details in production
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal Server Error',
                ];

                // Set a default status code
                $statusCode = 500;

                // Customize response for different exception types
                if ($e instanceof ModelNotFoundException) {
                    $response['message'] = 'Resource not found.';
                    $statusCode = 404;
                } elseif($e instanceof NotFoundHttpException) {
                    $response['message'] = 'Endpoint not found.';
                    $statusCode = 404;
                } elseif ($e instanceof AuthenticationException) {
                    $response['message'] = 'Unauthenticated.';
                    $statusCode = 401;
                } elseif ($e instanceof AuthorizationException) {
                    $response['message'] = 'Unauthorized.';
                    $statusCode = 403;
                } elseif ($e instanceof ValidationException) {
                    $response['message'] = 'Validation failed.';
                    $response['errors'] = $e->errors();
                    $statusCode = 422;
                }

                // Check if the request expects a JSON response
                if ($request->expectsJson()) {
                    return response()->json($response, $statusCode);
                }

                return response()->json($response, $statusCode);

            }

        });
        // End of render customized error message

    })->create();

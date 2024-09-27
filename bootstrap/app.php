<?php

use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\VerifyApiKey;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [
            ForceJsonResponse::class,
            VerifyApiKey::class
        ]);

        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/v*')) {
                return true;
            }

            return $request->expectsJson();
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->validator->errors(),
                    'code' => $e->status,
                    'dev_message' => !app()->environment(['production']) ? $e->getMessage() : null,
                    'stack_trace' => !app()->environment(['production']) ? $e->getTrace() : null,
                ], $e->status);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => __('Record not found.'),
                    'code' => 404,
                    'dev_message' => !app()->environment(['production']) ? $e->getMessage() : null,
                    'stack_trace' => !app()->environment(['production']) ? $e->getTrace() : null,
                ], 404);
            }
        });

        $exceptions->render(function (HttpResponseException $e, Request $request) {
            if ($request->wantsJson()) {
                $response = $e->getResponse();
                return response()->json([
                    'message' => $e->getMessage(),
                    'code' => $response->getStatusCode(),
                    'dev_message' => !app()->environment(['production']) ? $e->getMessage() : null,
                    'stack_trace' => !app()->environment(['production']) ? $e->getTrace() : null,
                ], $response->getStatusCode());
            }
        });
    })->create();

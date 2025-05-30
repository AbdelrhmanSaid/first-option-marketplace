<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(function () {
        if (app()->runningUnitTests()) {
            config(['redot.features.website-api.enabled' => true]);
            config(['redot.features.dashboard-api.enabled' => true]);
            config(['redot.features.webiste.enabled' => true]);
            config(['redot.features.dashboard.enabled' => true]);
        }

        // Load the API routes for the website and dashboard
        Route::middleware('api')->as('api.')->prefix('api')->group(function () {
            if (config('redot.features.website-api.enabled')) {
                Route::as('website.')
                    ->group(base_path('routes/api/website.php'));
            }

            if (config('redot.features.dashboard-api.enabled')) {
                Route::as('dashboard.')
                    ->prefix(config('redot.features.dashboard-api.prefix'))
                    ->group(base_path('routes/api/dashboard.php'));
            }
        });

        // Load the global routes
        Route::as('global.')
            ->group(base_path('routes/global.php'));

        // Load the website and dashboard routes
        $group = Route::middleware('web');

        if (config('redot.append_locale_to_url')) {
            $group->prefix('{locale}')->where(['locale' => '([a-zA-Z]{2})']);
        }

        $group->group(function () {
            if (config('redot.features.webiste.enabled')) {
                Route::as('website.')
                    ->group(base_path('routes/website.php'));
            }

            if (config('redot.features.dashboard.enabled')) {
                Route::as('dashboard.')
                    ->prefix(config('redot.features.dashboard.prefix'))
                    ->middleware('dashboard')
                    ->group(base_path('routes/dashboard.php'));
            }
        });

        // Load the fallback route
        Route::fallback(\App\Http\Controllers\FallbackController::class)->middleware('web');
    })

    ->withCommands([__DIR__ . '/../routes/console.php'])

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(remove: [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\Localization::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\EnsureDependenciesBuilt::class,
        ]);

        $middleware->group('dashboard', [
            \App\Http\Middleware\Dashboard\RoutePermission::class,
            \App\Http\Middleware\Dashboard\Locked::class,
        ]);

        $middleware->api(append: [
            'throttle:api',
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'verified' => \App\Http\Middleware\Website\EnsureEmailIsVerified::class,
            'publisher' => \App\Http\Middleware\Publisher::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request) {
            return $request->expectsJson() || $request->is('api/*');
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->expectsJson() && ! $request->is('api/*')) {
                return null;
            }

            $code = match (true) {
                $e instanceof \Symfony\Component\HttpKernel\Exception\HttpException => $e->getStatusCode(),
                $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => 404,
                $e instanceof \Illuminate\Validation\ValidationException => 422,
                $e instanceof \Illuminate\Auth\AuthenticationException => 401,
                $e instanceof \Illuminate\Auth\Access\AuthorizationException => 403,
                default => 500,
            };

            $message = $e->getMessage() ?: match ($code) {
                400 => 'Bad Request',
                401 => 'Unauthorized',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Payload Too Large',
                414 => 'URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Range Not Satisfiable',
                417 => 'Expectation Failed',
                418 => 'I\'m a teapot',
                421 => 'Misdirected Request',
                422 => 'Unprocessable Entity',
                423 => 'Locked',
                424 => 'Failed Dependency',
                425 => 'Too Early',
                426 => 'Upgrade Required',
                428 => 'Precondition Required',
                429 => 'Too Many Requests',
                431 => 'Request Header Fields Too Large',
                451 => 'Unavailable For Legal Reasons',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                506 => 'Variant Also Negotiates',
                507 => 'Insufficient Storage',
                508 => 'Loop Detected',
                510 => 'Not Extended',
                511 => 'Network Authentication Required',
                default => 'Something went wrong',
            };

            $payload = match (true) {
                $e instanceof \Illuminate\Validation\ValidationException => $e->validator->errors()->toArray(),
                config('app.debug') => ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine(), 'trace' => $e->getTrace()],
                default => [],
            };

            return response()->json([
                'code' => $code,
                'success' => false,
                'message' => $message,
                'payload' => $payload,
            ], $code);
        });
    })->create();

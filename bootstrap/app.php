<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\GlobalExceptionMiddleware;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        api: __DIR__ . '/../routes/api.php',  // Registers API routes
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->append(GlobalExceptionMiddleware::class);

        // Register your middleware
        $middleware->alias([
            'access.token' => \App\Http\Middleware\AuthenticateAccessToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

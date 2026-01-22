<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/**
 * Bootstrap and configure the Laravel application instance.
 *
 * This file defines routing, middleware aliases, and exception handling
 * before creating the application.
 */
return Application::configure(basePath: dirname(__DIR__))

    // -------------------------------------------------
    // ROUTING CONFIGURATION
    // -------------------------------------------------
    // Register route files for web, API, console commands,
    // and a simple health check endpoint.
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    // -------------------------------------------------
    // MIDDLEWARE CONFIGURATION
    // -------------------------------------------------
    // Define middleware aliases for convenient usage in routes.
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.custom' => \App\Http\Middleware\CustomAuth::class,
        ]);
    })

    // -------------------------------------------------
    // EXCEPTION HANDLING CONFIGURATION
    // -------------------------------------------------
    // Customize exception handling here if needed.
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    // Create and return the configured application instance
    ->create();

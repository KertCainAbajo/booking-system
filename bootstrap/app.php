<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Opening a members-only page while signed out (or after the session expired): say why
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if (! $request->expectsJson()) {
                session()->flash('toast', ['type' => 'info', 'message' => 'Please sign in to continue.']);
            }

            return route('login');
        });

        // Render (and similar hosts) end HTTPS at their proxy and forward plain HTTP to the app.
        // Trusting the proxy's X-Forwarded-* headers lets Laravel know the visitor is on HTTPS.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

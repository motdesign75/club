<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ✅ Stripe Webhooks dürfen nicht durch CSRF laufen (sonst 419)
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
            'stripe/*',
        ]);

        // ✅ Middleware-Aliases
        $middleware->alias([
            'member.limit' => \App\Http\Middleware\EnsureMemberLimitNotExceeded::class,

            // 🔥 NEU: Paywall Middleware
            'tenant.subscribed' => \App\Http\Middleware\EnsureTenantIsSubscribed::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
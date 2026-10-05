<?php

use App\Exceptions\BookingException;
use App\Http\Middleware\EnsureUserHasRole;
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
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Expected business-rule failures (sales not started, sold out,
        // not cancellable...) become a normal redirect back with a flash
        // error message instead of an exception page. This runs before
        // the default debug/Ignition handling, so it applies even with
        // APP_DEBUG=true locally.
        $exceptions->render(function (BookingException $e, $request) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        });

        // Everything else keeps Laravel's normal behaviour: genuinely
        // unexpected exceptions are still logged (see config/logging.php)
        // and, once APP_DEBUG=false, are shown through the custom
        // resources/views/errors/500.blade.php page automatically.
    })->create();

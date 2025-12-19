<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
    )

    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('orders:mark-overdue')->hourly();
        $schedule->command('orders:deadline-reminders --hours=24')->hourly();
        $schedule->command('orders:check-sla')->everyThirtyMinutes();
    })

    ->withMiddleware(function (Middleware $middleware) {

        // === ROLE MIDDLEWARE REGISTER ===
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // GLOBAL
        $middleware->append([
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        ]);

        // WEB GROUP
        $middleware->web([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);

        // API GROUP
        $middleware->api([
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
        ]);

        // ALIAS
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

<?php

use App\Http\Middleware\EnsureAdminAuthentication;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('orders:release-expired-stock')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureAdminAuthentication::class,
        ]);

        // Middleware 'auth' bawaan Laravel me-redirect ke route bernama 'login',
        // padahal proyek ini memakai 'auth.login' (pelanggan) dan 'admin.login' (panel admin).
        $middleware->redirectGuestsTo(function (Request $request): string {
            if ($request->is('admin', 'admin/*')) {
                return route('admin.login');
            }

            return route('auth.login');
        });

        $middleware->validateCsrfTokens(except: [
            'payments/midtrans/notification',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

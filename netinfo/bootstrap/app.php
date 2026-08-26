<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
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
            'role' => RoleMiddleware::class,
        ]);

        // Tanpa ini, user yang sudah login membuka /login akan di-redirect ke "/"
        // (fallback bawaan karena tidak ada route bernama "dashboard"/"home")
        // dan terjadi infinite loop / <-> /login.
        RedirectIfAuthenticated::redirectUsing(function ($request) {
            return match ($request->user()?->role) {
                'admin' => route('admin.dashboard'),
                'technician' => route('technician.dashboard'),
                default => route('customer.dashboard'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

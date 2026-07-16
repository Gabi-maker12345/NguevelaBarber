<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

foreach ([
    'APP_CONFIG_CACHE',
    'APP_EVENTS_CACHE',
    'APP_PACKAGES_CACHE',
    'APP_ROUTES_CACHE',
    'APP_SERVICES_CACHE',
] as $cachePathVariable) {
    if (getenv($cachePathVariable) === '') {
        putenv($cachePathVariable);
        unset($_ENV[$cachePathVariable], $_SERVER[$cachePathVariable]);
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
       
        $middleware->redirectUsersTo(function ($request) {
            $user = $request->user();

            if ($user instanceof \App\Models\Admin) {
                return route('pages.adminDashboard');
            }

            if ($user instanceof \App\Models\Barbearia) {
                return route('pages.barbeariaDashboard');
            }

            return route('pages.userDashboard');
        });

        $middleware->redirectGuestsTo('/login');
    })
    ->withExceptions(function (Exceptions $exceptions) {
 
    })->create();

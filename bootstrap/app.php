<?php

use App\Http\Middleware\AdminRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::namespace('App\Http\Controllers\Api')
            ->prefix('doctor')
            ->group(base_path('routes/Api/doctor.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([

            'admin' =>AdminRole::class
             
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

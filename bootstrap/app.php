<?php

use App\Http\Middleware\ApiLocalization;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsDoctor;
use App\Http\Middleware\IsPatient;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
       using: function () {
           
                 Route::namespace('App\Http\Controllers\Api')
                 ->prefix('api/admin')
                 ->group(base_path('routes/Api/admin.php'));

           
                Route::namespace('App\Http\Controllers\Api')
                 ->prefix('api/doctor')
                 ->group(base_path('routes/Api/doctor.php'));

          
                 Route::namespace('App\Http\Controllers\Api')
                 ->prefix('api/patient')
                 ->group(base_path('routes/Api/patient.php'));
  
                  Route::namespace('App\Http\Controllers')
                 ->prefix('api/auth')
                 ->group(base_path('routes/api.php'));
        },

    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'api_localization' => ApiLocalization::class,
         'IsAdmin' => IsAdmin::class,
         'IsPatient'=>IsPatient::class,
         'IsDoctor'=>IsDoctor::class
    ]);
       
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

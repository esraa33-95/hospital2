<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {

            Route::prefix('doctor')
            ->group(base_path('routes/Api/front/doctor.php'));

            Route::prefix('patient')
            ->group(base_path('routes/Api/front/patient.php')); 

            Route::prefix('admin')
               ->group(base_path('routes/Api/admin/admin.php')); 

               Route::prefix('list')
               ->group(base_path('routes/Api/front/list.php')); 

           
            Route::prefix('department')
            ->group(base_path('routes/Api/admin/department.php')); 
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        

       
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

<?php

use App\Http\Controllers\Api\admin\CertificateController;
use App\Http\Controllers\Api\front\DoctorController;
use App\Http\Controllers\Api\front\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsDoctor;


Route::middleware(['auth:sanctum', 'api_localization','IsDoctor'])->group(function () {

    Route::controller(DoctorController::class)->group(function () {
        Route::get('/', 'index');                  
        Route::post('/', 'create');                 
        Route::get('/{id}', 'show');               
        Route::patch('/', 'updatename');       
        Route::delete('/', 'delete');     
        // Route::get('filter', 'filterDoctors');    
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/', 'userprofile');      
        Route::put('/{id}', 'update');           
        Route::post('/', 'changePassword');
        Route::delete('/', 'deleteAccount');
         Route::post('{id}', 'uploadfile');  
        // Route::post('rate/{id}', 'rate');         
    });

    Route::controller(CertificateController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});

      
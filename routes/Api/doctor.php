<?php

use App\Http\Controllers\Api\front\DoctorController;
use App\Http\Controllers\Api\front\UserController;
use App\Http\Controllers\Api\front\CertificateController;
use App\Http\Controllers\Api\front\ExperienceController;
use App\Http\Controllers\Api\front\OrderController;
use App\Http\Controllers\Api\front\VisitController;
use App\Http\Controllers\Api\front\WalletController;
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
         // Route::post('rate/{id}', 'rate');
      
    });

     Route::prefix('certificate')->controller(CertificateController::class)->group(function () {
        
        Route::post('{id}', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
      
    });
    

     Route::prefix('experience')->controller(ExperienceController::class)->group(function () {
      
        Route::post('/{id}', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
      
    });

     Route::prefix('visits')->controller(VisitController::class)->group(function () {
      
        Route::post('/', 'store');
        Route::post('/{id}', 'active');
        Route::get('/{id}', 'show');
        Route::get('/{id}', 'index');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
       
      
    });
      
// show orders for doctors     
 Route::prefix('orders')->controller(OrderController::class)->group(function () {
      
        Route::post('/{id}', 'acceptorders');
        Route::post('/reject/{id}', 'rejectorder');
        Route::get('filter/{id}', 'filter');
        Route::post('/status/{id}', 'changestatus');
         
    
    });
    

    Route::prefix('wallet')->controller(WalletController::class)->group(function () {
      Route::post('/{id}', 'deposit');
      
    });


});

      
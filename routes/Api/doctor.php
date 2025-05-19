<?php

use App\Http\Controllers\Api\front\DoctorController;
use App\Http\Controllers\Api\front\UserController;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth:sanctum', 'api_localization'])->group(function () {

    Route::controller(DoctorController::class)->group(function () {
        Route::get('/', 'index');                  
        Route::post('/', 'create');                 
        Route::get('/{id}', 'show');               
        Route::patch('/', 'updatename');       
        Route::delete('/', 'delete');          
        // Route::get('filter', 'filterDoctors');    
    });

    Route::prefix('profile')->controller(UserController::class)->group(function () {
        Route::get('/', 'userprofile');      
        Route::patch('/', 'update');           
        Route::post('/', 'changePassword');
        Route::delete('/', 'deleteAccount');
         Route::post('/', 'uploadimage');  
        // Route::post('rate/{id}', 'rate');         
    });

});

      
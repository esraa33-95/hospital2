<?php

use App\Http\Controllers\Api\front\PatientController;
use App\Http\Controllers\Api\front\UserController;
use Illuminate\Support\Facades\Route;


    
Route::prefix('patients')->middleware(['auth:sanctum', 'api_localization'])->group(function () {

    Route::controller(PatientController::class)->group(function () {
        Route::get('/', 'index');                  
        Route::post('/', 'create');                 
        Route::get('/{id}', 'show');               
        Route::patch('/{id}', 'updatename');       
        Route::delete('/{id}', 'delete');          
        // Route::get('filter', 'filterDoctors');    
    });

    Route::prefix('profile')->controller(UserController::class)->group(function () {
        Route::get('index', 'userprofile');      
        Route::post('update', 'update');           
        Route::post('changepassword', 'changePassword');
        Route::post('uploadimage', 'uploadimage');
        Route::delete('deleteaccount', 'deleteAccount');
        // Route::post('rate/{id}', 'rate');         
    });

});
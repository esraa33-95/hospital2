<?php

use App\Http\Controllers\Api\admin\SurgeryController;
use App\Http\Controllers\Api\front\PatientController;
use App\Http\Controllers\Api\front\UserController;
use Illuminate\Support\Facades\Route;


    
Route::middleware(['auth:sanctum', 'api_localization','IsPatient'])->group(function (){

    Route::controller(PatientController::class)->group(function () {
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
        Route::post('/{id}', 'uploadimage');
        Route::delete('/', 'deleteAccount');
        // Route::post('rate/{id}', 'rate');         
    });

//surgery
 Route::controller(SurgeryController::class)->group(function () {
        Route::get('/','index');                  
        Route::post('/', 'create');                 
        Route::get('/{id}', 'show');               
        Route::put('/', 'update');       
        Route::delete('/', 'delete');          
          
    });




});
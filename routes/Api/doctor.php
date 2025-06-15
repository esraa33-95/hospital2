<?php


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
        Route::post('{id}','uploadfile'); 
         // Route::post('rate/{id}', 'rate');
         
        //certificate
        Route::post('/certificate/{id}', 'addcertificate');
        Route::get('/{id}', 'showcertificate');
        Route::put('/cert/{id}', 'updatecertificate');
        Route::delete('/{id}', 'deletecertificate');


       //experience
        Route::post('/{id}', 'addexperience');
        Route::get('/{id}', 'showexperience');
        Route::put('/{id}', 'updateexperience');
        Route::delete('/{id}', 'deleteexperience');



         
        
        
    });

    



});

      
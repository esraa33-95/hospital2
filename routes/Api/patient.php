<?php

use App\Http\Controllers\Api\front\AllergyController;
use App\Http\Controllers\Api\front\BloodController;
use App\Http\Controllers\Api\front\DiseaseController;
use App\Http\Controllers\Api\front\PatientController;
use App\Http\Controllers\Api\front\SurgeryController;
use App\Http\Controllers\Api\front\UserController;
use App\Http\Controllers\Api\front\AdressController;
use App\Http\Controllers\Api\front\OrderController;
use App\Http\Controllers\Api\front\WalletController;
use Illuminate\Support\Facades\Route;


    
Route::middleware(['auth:sanctum','api_localization','IsPatient'])->group(function (){

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
        Route::delete('/', 'deleteAccount');
        // Route::post('rate/{id}', 'rate');         
    });

//bloodtype
 Route::prefix('bloods')->controller(BloodController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'show');                               
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });

//disease
Route::prefix('diseases')->controller(DiseaseController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'show');                               
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });    
//allergy
Route::prefix('allergies')->controller(AllergyController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'show');                               
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });

//surgery
    Route::prefix('surgeries')->controller(SurgeryController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'show');                               
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });


//address
Route::prefix('addresses')->controller(AdressController::class)->group(function () {                 
        Route::post('/{id}', 'store');  
        Route::get('/{id}', 'show');                               
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });

    //orders
    Route::prefix('orders')->controller(OrderController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'orders'); 
        Route::post('/cancel/{id}', 'cancelorder');
        Route::get('filter/{id}', 'filter');
                      
    });


//wallet
Route::prefix('wallet')->controller(WalletController::class)->group(function () {
      Route::post('/{id}', 'deposit');
      
    });

 });
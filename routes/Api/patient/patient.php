<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Api\front\PatientController;
use App\Http\Controllers\Api\front\UserController;
use Illuminate\Support\Facades\Route;




Route::controller(PatientController::class)->prefix('patient')
    ->middleware('auth:sanctum')->group(function () {

        Route::get('index', 'index');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::patch('updatename', 'updatename');
        Route::delete('delete','delete');
        
        Route::controller(UserController::class)->prefix('profile')

        ->middleware('auth:sanctum')->group(function () {
            Route::get('index','userprofile');
            Route::post('update','update');  
            Route::post('changepassword', 'changePassword');
            Route::post('uploadimage', 'uploadimage');
            Route::delete('deleteaccount', 'deleteAccount');
    }); 

});
    
<?php

use App\Http\Controllers\Api\front\AuthController;
use App\Http\Controllers\Api\front\ListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


//authentication
Route::middleware('api_localization')->controller(AuthController::class)->group(function () {
    Route::post('auth/register','register');
    Route::post('auth/login','login');
    Route::post('auth/sendotp','sendotp');
    Route::post('auth/verify-email','verifyEmailOtp');
    Route::post('auth/reset-password','resetpassword');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', 'logout');
    });
 
});

//lists of doctors and departments
Route::controller(ListController::class)->prefix('lists')
    ->middleware('auth:sanctum')->group(function () {   
        Route::get('departments', 'departments');
         Route::get('doctors', 'doctors');
        
});
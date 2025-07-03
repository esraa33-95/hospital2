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
    Route::post('register','register');
    Route::post('login','login');
    Route::post('sendotp','sendotp');
    Route::post('verify-email','verifyEmailOtp');
    Route::post('reset-password','resetpassword');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'logout');
    });
 
});


Route::middleware('api_localization')->controller(ListController::class)->middleware('auth:sanctum')->group(function () {   
        Route::get('departments', 'departments');
        Route::get('doctors', 'doctors');
        Route::get('patients','patients');
        Route::get('diseases','disease');
        Route::get('allergies','allergy');
        Route::get('blood','blood');
        Route::get('surgeries','surgery');
        Route::get('countries','countries');
        Route::get('cities','cities');
        Route::get('areas','areas'); 
        Route::get('banners','banners'); 
        Route::get('visits','visits'); 
        
});
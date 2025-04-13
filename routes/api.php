<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//register
Route::controller(AuthController::class)->group(function () {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
    Route::post('auth/password/email','sendPasswordEmail');
    Route::post('auth/reset/password','reset')->name('password.reset');
    
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', 'logout');
    });
 
});



  //  Route::get('patient/profile', [PatientController::class, 'index']);


//patient
Route::controller(PatientController::class)->group(function () {
   
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('patient/{id}','show');
        Route::post('patient/edit/{id}','update'); 
        Route::post('change-password', 'changePassword');
        Route::delete('delete-account', 'deleteAccount');
    });
  
});



//doctor
Route::controller(DoctorController::class)->group(function () {
  
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('doctor/profile/{id}','show');
        Route::post('doctor/edit/{id}','update');  
        Route::post('change-password', 'changePassword');
        Route::delete('delete-account', 'deleteAccount');
    });
  
});



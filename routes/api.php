<?php

use App\Http\Controllers\Api\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PatientController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//register
Route::controller(AuthController::class)->group(function () {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
    Route::post('auth/password/email','sendPasswordEmail');
    Route::post('auth/reset/password','reset')->name('password.reset');
    Route::post('auth/verify-email',  'verifyEmailOtp');
 // Route::post('auth/send-email-otp',  'sendEmailOtp');
     
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', 'logout');
    });
 
});

  
//patient
Route::controller(PatientController::class)->group(function () {
    Route::get('patient/profile/{id}','show');

    Route::middleware('auth:sanctum')->group(function () {    
        Route::post('patient/edit/{id}','update'); 
        Route::post('change-password', 'changePassword');
        Route::delete('delete-account', 'deleteAccount');
    });
  
});


//doctor
// Route::controller(DoctorController::class)->group(function () {
//            Route::get('doctor/profile/{id}','show');

//     Route::middleware('auth:sanctum')->group(function () {
//         Route::post('doctor/edit/{id}','update');  
//         Route::post('change-password', 'changePassword');
//         Route::delete('delete-account', 'deleteAccount');
//     });
  
// });

//admin
Route::controller(AdminController::class)->group(function () {
    Route::post('admin/login', 'login');
    
    Route::middleware('auth:sanctum','admin')->group(function () {
        Route::post('admin/logout', 'logout');
        Route::post('admin/changedata/{id}', 'changedata');
       
       
    });
});





<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//register
Route::controller(AuthController::class)->group(function () {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', 'logout');
    });
 
});


// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('patient/profile', [PatientController::class, 'index']);

// });


Route::controller(PatientController::class)->group(function () {
    
    Route::get('patient/{id}','show');
    Route::post('patient/edit/{id}','update');   //user,patient

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('change-password', 'changePassword');
        Route::delete('delete-account', 'deleteAccount');
    });
  
});
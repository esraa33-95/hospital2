<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//authentication
Route::controller(AuthController::class)->group(function () {
    Route::post('auth/register', 'register');
    Route::post('auth/login', 'login');
    Route::post('auth/password/email','forgetpassword');
    Route::post('auth/verify-email',  'verifyEmailOtp');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', 'logout');
    });
 
});
<?php

namespace App\Http\Controllers\Api\front\user;

use App\Http\Controllers\Api\front\user\UserController;
use Illuminate\Support\Facades\Route;


Route::controller(UserController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('profile','userprofile');
        Route::post('edit','update');  
        Route::post('change-password', 'changePassword');
        Route::delete('delete-account', 'deleteAccount');
        
  
});
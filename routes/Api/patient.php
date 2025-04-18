<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;



Route::controller(UserController::class)->middleware('auth:sanctum')->group(function () {
        Route::get('profile','userprofile');
        Route::post('edit','update');  
        Route::post('change-password', 'changePassword');
        Route::delete('delete-account', 'deleteAccount');
  
});
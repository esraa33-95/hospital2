<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;



Route::controller(UserController::class)->group(function () {
        Route::get('{id}','show');
        Route::post('edit/{id}','update');  
        Route::post('change-password', 'changePassword');
        Route::delete('delete-account', 'deleteAccount');
  
});
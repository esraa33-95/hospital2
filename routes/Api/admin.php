<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;


Route::controller(AdminController::class)->group(function () {
    Route::post('login', 'login');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'logout');
        Route::post('changedata', 'changedata');
        Route::post('update', 'update');
           
    });
});
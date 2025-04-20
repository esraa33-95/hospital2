<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;


Route::controller(AdminController::class)->group(function () {
   
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('changedata', 'changedata');
        Route::post('update', 'update');
        Route::post('departments', 'departments');  
        Route::post('assign-role/{id}', 'assignRole');
    });
});
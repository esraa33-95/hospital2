<?php

namespace App\Http\Controllers\Api\front\project;

use App\Http\Controllers\Api\front\project\DepartmentController;
use Illuminate\Support\Facades\Route;


Route::controller(DepartmentController::class)->group(function () {
           Route::post('create', 'create');
           Route::post('index/{search?}', 'index');
           Route::post('show/{id}', 'show');  
           
    Route::middleware('auth:sanctum')->group(function () {
      
        Route::post('update/{id}', 'update');
        Route::delete('delete/{id}', 'destroy');
    });
});
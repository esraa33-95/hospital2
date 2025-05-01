<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Api\Admin\AdminController;
use Illuminate\Support\Facades\Route;



Route::controller(AdminController::class)->group(function () {
            Route::post('login', 'login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('update', 'update');
        Route::post('logout', 'logout');
        
    });
});
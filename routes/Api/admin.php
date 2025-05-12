<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\admin\DepartmentController;
use App\Http\Controllers\Api\admin\DoctorController;
use App\Http\Controllers\Api\admin\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\isadmin;

//login
Route::post('admin/login', [AdminController::class, 'login']);


Route::prefix('admin')->middleware('auth:sanctum')->group(function () {

    Route::controller(AdminController::class)->group(function () {
        Route::post('update', 'update');
        Route::post('logout', 'logout');
    });

     Route::prefix('departments')->middleware('isadmin')->controller(DepartmentController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
   
    Route::prefix('doctors')->middleware('isadmin')->controller(DoctorController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::prefix('patients')->middleware('isadmin')->controller(PatientController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
    
});




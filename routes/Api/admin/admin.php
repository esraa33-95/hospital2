<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\admin\DepartmentController;
use Illuminate\Support\Facades\Route;

//login
Route::post('admin/login', [AdminController::class, 'login']);

Route::controller(AdminController::class)->prefix('admin')
    ->middleware('auth:sanctum')->group(function () {
    Route::put('update', 'update');
    Route::post('logout', 'logout');    

Route::controller(DepartmentController::class)->prefix('department')
    ->middleware('auth:sanctum')->group(function () {
    Route::post('create', 'create');
    Route::get('index/{search?}', 'index');
    Route::get('show/{id}', 'show'); 
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});
});



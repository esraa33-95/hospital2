<?php

namespace App\Http\Controllers\Api\doctor;

use App\Http\Controllers\Api\front\DoctorController;
use Illuminate\Support\Facades\Route;



Route::controller(DoctorController::class)->prefix('doctor')

    ->middleware('auth:sanctum')->group(function () {

        Route::get('index', 'index');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::patch('updatename', 'updatename');
        Route::delete('delete','delete');
        
});
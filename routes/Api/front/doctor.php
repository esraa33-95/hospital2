<?php

namespace App\Http\Controllers\Api\front;

use Illuminate\Support\Facades\Route;




Route::controller(DoctorController::class)->middleware('auth:sanctum')->group(function () {

        Route::get('index', 'index');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::post('updatename', 'updatename');
        Route::delete('delete','delete');
        
});
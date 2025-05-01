<?php

namespace App\Http\Controllers\Api\front\user;

use Illuminate\Support\Facades\Route;




Route::controller(PatientController::class)->middleware('auth:sanctum')->group(function () {
        
        Route::get('index', 'index');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::post('updatename/{id}', 'updatename');
        Route::delete('delete/{id}','delete');
        
});
    
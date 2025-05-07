<?php

namespace App\Http\Controllers\Api\patient;

use App\Http\Controllers\Api\front\PatientController;
use Illuminate\Support\Facades\Route;




Route::controller(PatientController::class)->prefix('patient')
    ->middleware('auth:sanctum')->group(function () {

        Route::get('index', 'index');
        Route::post('create', 'create');
        Route::get('show/{id}', 'show');
        Route::patch('updatename', 'updatename');
        Route::delete('delete','delete');
        
});
    
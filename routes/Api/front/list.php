<?php

namespace App\Http\Controllers\Api\front;

use Illuminate\Support\Facades\Route;



Route::controller(ListController::class)->group(function () {
        
        Route::get('departments', 'departments');
         Route::get('doctors', 'doctors');
        
});
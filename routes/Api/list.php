<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;




Route::controller(listController::class)->group(function () {

        Route::get('departments', 'departments');

         Route::get('doctors', 'doctors');
});
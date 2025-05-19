<?php



use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\admin\DepartmentController;
use App\Http\Controllers\Api\admin\DoctorController;
use App\Http\Controllers\Api\admin\PatientController;
use App\Http\Controllers\Api\admin\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;


//login
Route::post('/login',[AdminController::class,'login']);

Route::middleware(['auth:sanctum','api_localization','IsAdmin'])->group(function () {

    Route::controller(AdminController::class)->group(function () {
        Route::post('/', 'update');
        Route::post('/', 'logout');
    });

     Route::prefix('departments')->controller(DepartmentController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
   
    Route::prefix('doctors')->controller(DoctorController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::prefix('patients')->controller(PatientController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


     Route::prefix('reports')->controller(ReportController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
    
});




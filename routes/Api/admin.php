<?php



use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\admin\AllergyController;
use App\Http\Controllers\Api\admin\BloodController;
use App\Http\Controllers\Api\admin\CertificateController;
use App\Http\Controllers\Api\admin\DepartmentController;
use App\Http\Controllers\Api\admin\DiseaseController;
use App\Http\Controllers\Api\admin\DoctorController;
use App\Http\Controllers\Api\admin\ExperienceController;
use App\Http\Controllers\Api\admin\PatientController;
use App\Http\Controllers\Api\admin\ReportController;
use App\Http\Controllers\Api\admin\SurgeryController;
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
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    Route::prefix('patients')->controller(PatientController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


    //  Route::prefix('reports')->controller(ReportController::class)->group(function ()  {
    //     Route::post('/', 'store');
    //     Route::get('/', 'index');
    //     Route::get('/{id}', 'show');
    //     Route::put('/{id}', 'update');
    //     Route::delete('/{id}', 'destroy');
    // });

    Route::prefix('certificate')->controller(CertificateController::class)->group(function () {
        Route::post('/{id}', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
    });

 Route::prefix('experience')->controller(ExperienceController::class)->group(function () {
        Route::post('/{id}', 'store');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'delete');
    });
   
    
    //surgery
 Route::prefix('surgery')->controller(SurgeryController::class)->group(function (){                 
        Route::post('/{id}', 'store');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });
//allergy
 Route::prefix('allergy')->controller(AllergyController::class)->group(function () {                 
        Route::post('/{id}', 'store');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });
//disease
    Route::prefix('disease')->controller(DiseaseController::class)->group(function () {                 
        Route::post('/{id}', 'store');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });
//blood
    Route::prefix('blood')->controller(BloodController::class)->group(function () {                 
        Route::post('/{id}', 'store');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });

});


 


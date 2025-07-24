<?php



use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\admin\AllergyController;
use App\Http\Controllers\Api\admin\AreaController;
use App\Http\Controllers\Api\admin\BannerController;
use App\Http\Controllers\Api\admin\BloodController;
use App\Http\Controllers\Api\admin\CityController;
use App\Http\Controllers\Api\admin\CountryController;
use App\Http\Controllers\Api\admin\DepartmentController;
use App\Http\Controllers\Api\admin\DiseaseController;
use App\Http\Controllers\Api\admin\DoctorController;
use App\Http\Controllers\Api\admin\OrderController;
use App\Http\Controllers\Api\admin\PatientController;
use App\Http\Controllers\Api\admin\ReportController;
use App\Http\Controllers\Api\admin\SurgeryController;
use App\Http\Controllers\Api\admin\VisitController;
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

     Route::prefix('reports')->controller(ReportController::class)->group(function ()  {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

    //surgery
 Route::prefix('surgeries')->controller(SurgeryController::class)->group(function (){                 
        Route::post('/', 'store'); 
        Route::get('/{id}', 'show'); 
         Route::get('/', 'index');                               
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });
//allergy
 Route::prefix('allergies')->controller(AllergyController::class)->group(function () {                 
        Route::post('/', 'store'); 
         Route::get('/{id}', 'show');  
         Route::get('/', 'index');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });
//disease
    Route::prefix('diseases')->controller(DiseaseController::class)->group(function () {                 
        Route::post('/', 'store');  
         Route::get('/{id}', 'show'); 
         Route::get('/', 'index');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });
//blood
    Route::prefix('bloods')->controller(BloodController::class)->group(function () {                 
        Route::post('/', 'store');  
         Route::get('/{id}', 'show'); 
         Route::get('/', 'index');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');          
          
    });


     Route::prefix('countries')->controller(CountryController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'show'); 
        Route::get('/', 'index');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');                
    });

 Route::prefix('cities')->controller(CityController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'show'); 
        Route::get('/', 'index');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');                
    });


 Route::prefix('areas')->controller(AreaController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'show'); 
        Route::get('/', 'index');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');                
    });


    //banner
    Route::prefix('banners')->controller(BannerController::class)->group(function () {                 
        Route::post('/', 'store'); 
        Route::put('/{id}', 'update');         
        Route::delete('/{id}', 'delete');          
          
    });

    Route::prefix('visits')->controller(VisitController::class)->group(function () {                 
        Route::post('/', 'store');  
        Route::get('/{id}', 'show'); 
        Route::get('/', 'index');                                
        Route::put('/{id}', 'update');       
        Route::delete('/{id}', 'delete');                
    });

    Route::prefix('orders')->controller(OrderController::class)->group(function () {                 
        Route::get('/', 'filterorders'); 
        Route::get('/transaction', 'transaction'); 
                     
    }); 

     

});


 


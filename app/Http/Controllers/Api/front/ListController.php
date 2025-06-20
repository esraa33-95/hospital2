<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Traits\Response;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Surgery;
use App\Models\Allergy;
use App\Models\Blood;
use App\Models\User;
use App\Transformers\front\DepartmentTransform;
use App\Transformers\front\UserTransform;
use App\Transformers\front\surgeryTransform;
use App\Transformers\front\AllergyTransform;
use App\Transformers\front\DiseaseTransform;
use App\Transformers\front\BloodTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class ListController extends Controller
{
    use Response;

    //department list
    public function departments(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = Department::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

     $department = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $department =  fractal()->collection($department)
                  ->transformWith(new DepartmentTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$department,200,['count' => $total]);

    }

//doctor list
    public function doctors(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip');  
       
    
        $query = User::where('user_type', 2)
                      ->whereHas('certificate')
                      ->with('certificate');
                      
        if ($search) 
        {
           $query->where('name','like', '%' . $search . '%');
        }

        $total = $query->count();

       $doctors = $query->skip($skip ?? 0)->take($take ?? $total)->get();

         $doctors = fractal()->collection($doctors)
                  ->transformWith(new UserTransform())
                  ->serializeWith(new ArraySerializer())
                  ->toArray();

      return $this->responseApi('',$doctors,200,['count' => $total] );

    }
    
    //patients
      public function patients(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip');  
        
        
        $query = User::where('user_type', 3)       
                       ->whereHas('surgeries')
                       ->with('surgeries');          
    
        if ($search) 
        {
           $query->where('name','like', '%' . $search . '%');
        }
        
        $total = $query->count();

       $patients = $query->skip($skip ?? 0)->take($take ?? $total)->get();

        $patients = fractal()->collection($patients)
                  ->transformWith(new UserTransform())
                  ->serializeWith(new ArraySerializer())
                  ->toArray();

    return $this->responseApi('',$patients,200,['count' => $total]);

    }

    //disease
  public function disease(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = Disease::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

     $disease = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $disease =  fractal()->collection($disease)
                  ->transformWith(new DiseaseTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$disease,200,['count' => $total]);

    }

//allergy
     public function allergy(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = allergy::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

    $allergy = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $allergy =  fractal()->collection($allergy)
                  ->transformWith(new AllergyTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$allergy,200,['count' => $total]);

    }

  //surgery
   public function surgery(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = Surgery::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

    $surgery = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $surgery =  fractal()->collection($surgery)
                  ->transformWith(new surgeryTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$surgery,200,['count' => $total]);

    }

    //blood
     public function blood(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = Blood::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

    $blood = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $blood =  fractal()->collection($blood)
                  ->transformWith(new BloodTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$blood,200,['count' => $total]);

    }



}

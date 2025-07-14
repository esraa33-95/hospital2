<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Traits\Response;
use App\Models\Department;
use App\Models\Disease;
use App\Models\Surgery;
use App\Models\Allergy;
use App\Models\Area;
use App\Models\Visit;
use App\Models\Blood;
use App\Models\Banner;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use App\Transformers\front\DepartmentTransform;
use App\Transformers\front\UserTransform;
use App\Transformers\front\surgeryTransform;
use App\Transformers\front\AllergyTransform;
use App\Transformers\front\AreaTransform;
use App\Transformers\front\DiseaseTransform;
use App\Transformers\front\BloodTransform;
use App\Transformers\front\BannerTransform;
use App\Transformers\front\CityTransform;
use App\Transformers\front\CountryTransform;
use App\Transformers\front\VisitTransform;
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
        $departmentId =  $request->input('department_id');
       
    
        $query = User::where('user_type', 2)->with('department');
                      
        // if ($search) 
        // {
        //    $query->where('name','like', '%' . $search . '%');
        // }

        if ($request->department_id) 
         {
           $query->where('department_id', $departmentId);
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

  //countries
   public function countries(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = Country::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

    $country = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $country =  fractal()->collection($country)
                  ->transformWith(new CountryTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$country,200,['count' => $total]);

    }


//city
 public function cities(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = City::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

    $city = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $city =  fractal()->collection($city)
                  ->transformWith(new CityTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$city,200,['count' => $total]);

    }


 //area
  public function areas(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = Area::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

    $area = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $area =  fractal()->collection($area)
                  ->transformWith(new AreaTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$area,200,['count' => $total]);

    }   

//banners
public function banners(Request $request)
    {
        $search = $request->input('search');
        $position = $request->input('position');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

          $query = Banner::query();

        if (in_array($position, ['doctor', 'patient']))
         {
              $query->where('position', $position);
        }
       
    $total = $query->count(); 

    $banner = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $banner =  fractal()->collection($banner)
                  ->transformWith(new BannerTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$banner,200,['count' => $total]);

    }  


//visits
     public function visits(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

        $user = auth()->user();

      $query = $user->visits()->wherePivot('active', true);

      if ($search) 
      {
       $query->whereTranslationLike('visit_type', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

    $visit = $query->skip($skip ?? 0)->take($take ?? $total)->get();

    $visit =  fractal()->collection($visit)
                  ->transformWith(new VisitTransform())
                  ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('',$visit,200,['count' => $total]);

    }  
}

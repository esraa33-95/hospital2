<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Traits\Response;
use App\Models\Department;
use App\Models\User;
use App\Transformers\front\DepartmentTransform;
use App\Transformers\front\UserTransform;

use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class ListController extends Controller
{
    use Response;

    //department list
    public function departments(Request $request)
    {
        $search = $request->input('search', null);
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
        $locale = $request->query('lang', app()->getLocale());

      $query = Department::query();

      if ($search) 
      {
       $query->whereTranslationLike('name', '%' . $search . '%', $locale);
      }

    $total = $query->count(); 

     $department = $query->skip($skip ?? 0)->take($take ?? 0)->get();

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
        $locale = $request->query('lang', app()->getLocale());
    
        $query = User::where('user_type', 2)
                       ->whereHas('department')        
                       ->with('department');          
    
        if ($search) 
        {
            $query->whereTranslationLike('name', '%' . $search . '%', $locale);
        }
        $total = $query->count();

       $doctors = $query->skip($skip ?? 0)->take($take ?? 0)->get();

         $doctors = fractal()->collection($doctors)
                  ->transformWith(new UserTransform())
                  ->serializeWith(new ArraySerializer())
                  ->toArray();

     return $this->responseApi('',$doctors,200,['count' => $total]);

    }
    

    
}

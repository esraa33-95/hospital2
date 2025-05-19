<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Traits\Response;
use App\Models\Department;
use App\Models\User;
use App\Transformers\UserTransform;
use DepartmentTransform;
use Illuminate\Http\Request;

class ListController extends Controller
{
    use Response;

    //department list
    public function departments(Request $request)
    {
        $search = $request->input('search', null);
        $take = $request->input('take'); 
        $skip = $request->input('skip',0); 

      $query = Department::query();

      if ($search) 
      {
        $query->where('name', 'like', '%' . $search . '%');
      }


    $total = $query->count(); 

    $department = $query->skip($skip ?? 0)->take($take ?? 0)->get();

     $department =  fractal()->collection($department)
                  ->transformWith(new DepartmentTransform())
                   ->toArray();

    return $this->responseApi('',$department,200,['count' => $total]);

    }

//doctor list
    public function doctors(Request $request)
    {
        $search = $request->input('search');
        $take = $request->input('take'); 
        $skip = $request->input('skip',0);  
    
        $query = User::where('user_type', 2)
            ->whereHas('department')        
            ->with('department');          
    
        if ($search) 
        {
            $query->where('name', 'like', '%' . $search . '%');
        }
    

        $total = $query->count();

        $doctors = $query->skip($skip ?? 0)->take($take ?? 0)->get();

         $doctors = fractal()->collection($doctors)
                  ->transformWith(new UserTransform())
                   ->toArray();

     return $this->responseApi('',$doctors,200,['count' => $total]);

    }
    

    
}

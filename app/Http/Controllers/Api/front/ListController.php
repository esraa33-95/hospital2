<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\UserResource;
use App\Traits\Response;
use App\Models\Department;
use App\Models\User;
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

      if (!$take || $take == 0)
     {
        return $this->responseApi('', DepartmentResource::collection([]), 200, ['count' => 0]);
    }

    $total = $query->count(); 

    $department = $query->skip($skip ?? 0)->take($take)->get();

    return $this->responseApi('',DepartmentResource::collection($department),200,['count' => $total]);

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
    
        if (!$take || $take == 0)
     {
        return $this->responseApi('', UserResource::collection([]), 200, ['count' => 0]);
    }

        $total = $query->count();

        $doctors = $query->skip($skip ?? 0)->take($take)->get();

     return $this->responseApi('',UserResource::collection($doctors),200,['count' => $total]);

    }
    

    
}

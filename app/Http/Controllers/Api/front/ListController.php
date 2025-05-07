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

    $total = $query->count(); 

    if ($skip) 
        {
            $query->skip($skip);
        }

    $department = $query->take($take)->get();

    if ($department->isEmpty()) 
    {
        return $this->responseApi(__('No department found.'), 404);
    }

    return response()->json([
        'data' => DepartmentResource::collection($department),
        'total' => $total,
        'skip' => $skip,
        'take' => $take,
    ]);

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

        if ($skip) 
        {
            $query->skip($skip);
        }
    
        $doctors = $query->skip($skip)->take($take)->get();
    
        if ($doctors->isEmpty()) 
        {
            return $this->responseApi(__('No doctor found.'), 404);
        }
    
        return response()->json([
            'data' => UserResource::collection($doctors),
            'total' => $total,
            'skip' => $skip,
            'take' => $take,
        ]);
    }
    

    
}

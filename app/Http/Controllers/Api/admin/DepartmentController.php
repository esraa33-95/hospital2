<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\CreateDepartment;
use App\Http\Requests\Api\admin\UpdateDepartment;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Traits\Response;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  use Response;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search');
    $take = $request->input('take'); 
    $skip = $request->input('skip',0);  
 
    $query = Department::query();
  
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });
    }

    $total = $query->count(); 

    if ($skip) 
    {
        $query->skip($skip);
    }
    
    $departments = $query->take($take)->get();

    return response()->json([
        'data' => DepartmentResource::collection($departments),
        'total' => $total,
        'skip' => $skip,
        'take' => $take,
    ]);
}
    
//create
    public function create(CreateDepartment $request)
    {
      $data = $request->validated();
    
    $department = Department::create($data);
    
    return  $this->responseApi(__('create department succefully'),$department,200);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::findOrFail($id);

        return  $this->responseApi(__('show department succefully'),$department,200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartment  $request, string $id)
    {
        $data = $request->validated();

        $department = Department::find($id);

        if(!$department)
        {
            return  $this->responseApi(__('no department found'),404); 
        }
    
        $department->update($data);

        return  $this->responseApi(__('update department succesfully'),$department,200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::find($id);

        if(!$department)
        {
            return  $this->responseApi(__('no department found'),404); 
        }
    
        if($department->users()->exists())
        {
            return  $this->responseApi(__('canot delete this department'),400); 
        }

        $department->delete();
        
        return  $this->responseApi(__('department delete successfully'),200); 
    }

    
}


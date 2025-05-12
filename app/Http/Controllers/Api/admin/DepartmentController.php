<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\CreateDepartment;
use App\Http\Requests\Api\admin\UpdateDepartment;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\UserResource;
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
    $skip = $request->input('skip');  
 
    $query = Department::query();
    
    if ($search)
     {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });
    }

     if (!$take || $take == 0)
     {
        return $this->responseApi('', DepartmentResource::collection([]), 200, ['count' => 0]);
    }

    $total = $query->count(); 

    $departments = $query->skip($skip ?? 0)->take($take)->get();
    

    return $this->responseApi('',DepartmentResource::collection($departments),200,['count' => $total]);
}
    

//create
    public function store(CreateDepartment $request)
    {
      $data = $request->validated();
    
    $department = Department::create($data);
    
    return  $this->responseApi(__('create department succefully'),$department,201);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::findOrFail($id);

        return  $this->responseApi('',$department,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartment  $request, string $id)
    {
        $data = $request->validated();

        $department = Department::findOrFail($id);
    
        $department->update([
            'name' => $data['name'] ?? $department->name
        ]);

        return  $this->responseApi(__('update department succesfully'),$department,200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        if($department->users()->exists())
        {
            return  $this->responseApi(__('canot delete this department'),403); 
        }

        $department->delete();
        
        return  $this->responseApi(__('department delete successfully'),204); 
    }

    
}


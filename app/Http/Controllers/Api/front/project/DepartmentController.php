<?php

namespace App\Http\Controllers\Api\front\project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\project\CreateDepartment;
use App\Http\Requests\Api\front\project\UpdateDepartment;
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

        $departments = Department::when($search ,function($q) use ($search){
        $q->where('name','like','%'.$search.'%');
        })->paginate(10);

        if($departments->isEmpty())
        {
            return  $this->responseApi(__('no department found'),404);

        }

       return  DepartmentResource::collection($departments);
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

        if(!$department)
        {
            return  $this->responseApi(__('no department found'),404); 
        }
    
        return  $this->responseApi(__('show department succefully'),$department,200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartment  $request, string $id)
    {
        $data = $request->validated();

        $department = Department::findOrfail($id);

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

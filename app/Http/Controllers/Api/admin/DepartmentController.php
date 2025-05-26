<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\StoreDepartment;
use App\Http\Requests\Api\admin\UpdateDepartment;
use App\Models\Department;
use App\Traits\Response;
use App\Transformers\Admin\DepartmentTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

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
    $locale = $request->query('lang', app()->getLocale());

    $query = Department::query();

      if ($search)
    {
        $query->whereTranslationLike('name', '%' . $search . '%', $locale);
    }

    $total = $query->count();

    $departments = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $departments =  fractal()->collection($departments)
                  ->transformWith(new DepartmentTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $departments, 200, ['count' =>$total]);
}


//create
public function store(StoreDepartment $request)
{
     $data = [
        'en' => ['name' => $request->name_en],
        'ar' => ['name' => $request->name_ar],
    ];

    $department = Department::create($data);

    $department = fractal($department, new DepartmentTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_department'), $department, 201);
}


    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {     
        $department = Department::findOrFail($id);

         $department = fractal()
                 ->item($department)
                 ->transformWith(new DepartmentTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$department,200);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateDepartment  $request, string $id)
    // {
    //     $data = $request->validated();

    //     $department = Department::findOrFail($id);
    
    //     $department->update([
    //         'name' => $data['name'] ?? $department->name
    //     ]);

    //     return  $this->responseApi(__('messages.update_department'),$department,200);
    // }

 public function update(UpdateDepartment $request, $id)
{
    $data = [
        'en' => ['name' => $request->name_en],
        'ar' => ['name' => $request->name_ar],
    ];

    $department = Department::findOrFail($id);

    $department->update($data);

    $department = fractal($department, new DepartmentTransform())
                  ->serializeWith(new ArraySerializer())
                  ->toArray();

    return $this->responseApi(__('messages.update_department'), $department);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        if($department->users()->exists())
        {
            return  $this->responseApi(__('messages.no_delete'),403); 
        }

        $department->delete();
        
        return  $this->responseApi(__('messages.delete_department'),204); 
    }

    
}


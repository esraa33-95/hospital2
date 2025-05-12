<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Response;
use App\Http\Requests\Api\front\RegisterRequest;
use App\Http\Requests\Api\front\updateUser;
use App\Http\Resources\UserResource;
use App\Traits\Common;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    use Response;
    use Common;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', null);
        $take = $request->input('take'); 
        $skip = $request->input('skip'); 
    
        $query = User::where('user_type', 2);

    if ($search) 
    {
        $query->where(function ($q) use ($search) {
             $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('mobile', 'like', '%' . $search . '%');
        });
    }

    if (!$take || $take === 0)
     {
        return $this->responseApi('', UserResource::collection([]), 200, ['count' => 0]);
    }

     $total = $query->count();
  
    $doctors = $query->skip($skip ?? 0)->take($take)->get();
    
    return $this->responseApi('',UserResource::collection($doctors),200,['count' => $total]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();
    
        if($request->hasFile('image'))
        {
            $data['image'] = $this->uploadFile($request->image,'assets/images');
        }

        $data['password'] = Hash::make($data['password']);  

         $doctor =  User::create($data);

         return $this->responseApi(__('doctor created successfully'),$doctor,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $doctor = User::withTrashed()
       ->where('user_type',2)
       ->where('id',$id)
       ->first();

         if(!$doctor)
       {
         return $this->responseApi(__('This doctor is deleted'),null, 403);
       }
       
       if ($doctor->trashed())
        {
           return $this->responseApi(__('This doctor is deleted'), 403);
        }

        return new UserResource($doctor);
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(updateUser $request ,string $id)
{
    $data = $request->validated();

    $doctor = User::withTrashed()
    ->where('user_type',2)
    ->where('id',$id)
    ->first();

         if(!$doctor)
       {
         return $this->responseApi(__('This doctor is deleted'),null, 403);
       }

    if ($doctor->trashed()) 
    {
        return $this->responseApi(__('Account has been deleted'),[], 403);
    }

   foreach (['name', 'email', 'mobile', 'department_id', 'user_type'] as $field)
    {
        if (isset($data[$field])) 
        {
            $doctor->$field = $data[$field];
        }
    }

     if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assets/images'); 
        }

        if(!isset($doctor->password ) && !Hash::check($doctor->password ,$data['password']) )
        {
          $doctor->password = Hash::make($data['password']);

        }

       $doctor->save();

    return $this->responseApi(__('doctor updated successfully'),$doctor,200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $doctor = User::withTrashed()
        ->where('user_type', 2) 
        ->where('id',$id)
        ->first();

         if(!$doctor)
       {
         return $this->responseApi(__('This doctor is deleted'),null, 403);
       }

    if ($doctor->trashed()) 
    {
        return $this->responseApi(__('Doctor has been deleted'), [],409); 
    }

    $doctor->delete();

    return $this->responseApi(__('Doctor deleted successfully'),[],200);
}

}

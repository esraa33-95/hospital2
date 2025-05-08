<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\RegisterRequest;
use App\Http\Requests\Api\front\Updatebyname;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Common;
use App\Traits\Response;
use Illuminate\Http\Request;
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
        $skip = $request->input('skip',0); 
    
        $query = User::where('user_type', 2);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('mobile', 'like', '%' . $search . '%');
        });
    }

    $total = $query->count(); 

       if ($skip) 
        {
            $query->skip($skip);
        }

    $doctor = $query->take($take)->get();

    if ($doctor->isEmpty()) 
    {
        return $this->responseApi(__('No doctors found.'), 404);
    }

    return response()->json([
        'data' => UserResource::collection($doctor),
        'total' => $total,
        'skip' => $skip,
        'take' => $take,
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(RegisterRequest $request)
    {
        $data = $request->validated();
    
        if($request->hasFile('image'))
        {
            $data['image'] = $this->uploadFile($request->image,'assets/images');
        }

        $data['password'] = Hash::make($data['password']);  

         $doctor =  User::create($data);

         return $this->responseApi(__('doctor created successfully'),$doctor,200);
    }

 
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $doctor = User::where('id',$id)
       ->where('user_type',2)
       ->first();

       if (!$doctor)
        {
        return $this->responseApi(__('doctor not found'), 404);
       }

        return new UserResource($doctor);

    }

    /**
     * Update the specified resource in storage.
     */
    public function updatename(Updatebyname $request)
{
    $request->validated();

    $uuid = $request->input('uuid');

    $doctor = User::withTrashed()
    ->where('uuid', $uuid)
    ->where('user_type',2)
    ->first();

    if (!$doctor) 
    {
        return $this->responseApi(__('doctor not found'), 404);
    }

    if ($doctor->trashed()) 
    {
        return $this->responseApi(__('Account has been deleted'), 403);
    }

    if($doctor->name !== $request->input('name'))
    {
        $doctor->name = $request->input('name');

    }

    $doctor->save();

    return $this->responseApi(__('doctor name updated successfully'), 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {

        $uuid = $request->input('uuid');
    
        $doctor = User::where('user_type',2)
        ->where('uuid', $uuid)
        ->first();
    
        if (!$doctor) 
        {
            return $this->responseApi(__('No doctor found'), 404);
        }

        $doctor->delete();
    
        return $this->responseApi(__('doctor deleted successfully'), 200);
    }
    
}

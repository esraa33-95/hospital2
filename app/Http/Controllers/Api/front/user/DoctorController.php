<?php

namespace App\Http\Controllers\Api\front\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\Updatebyname;
use App\Http\Requests\Api\front\user\RegisterRequest;
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
        $skip = $request->input('skip'); 
    
        $query = User::where('user_type', 2);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('mobile', 'like', '%' . $search . '%');
        });
    }

    $total = $query->count(); 

       if (!is_null($skip)) 
        {
            $query->skip($skip);
        }

    $doctor = $query->take($take)->get();

    if ($doctor->isEmpty()) {
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
    public function updatename(Updatebyname $request, $id)
{
    $request->validated();

    $types = $request->input('user_type');

    $doctor = User::where('user_type',$types)
    ->where('id', $id)
    ->whereNull('deleted_at')
    ->first();

    if (!$doctor) 
    {
        return $this->responseApi(__('doctor not found'), 404);
    }

    $doctor->name = $request->name;
    $doctor->save();

    return $this->responseApi(__('doctor name updated successfully'), 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request,$id)
{
    $types = $request->input('user_type');

     $doctor = User::where('user_type',$types)
     ->where('id', $id)
     ->first();

     if(!$doctor)
     {
        return $this->responseApi(__('no doctor is find'), 404);
     }

   $doctor->delete();

     return $this->responseApi(__('doctor delete successfully'),200);
}
}

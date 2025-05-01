<?php

namespace App\Http\Controllers\Api\front\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\admin\Updatebyname;
use App\Http\Requests\Api\front\user\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Common;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
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
    
        $query = User::where('user_type', 3);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%')
              ->orWhere('mobile', 'like', '%' . $search . '%');
        });
    }

    $total = $query->count(); 

    $users = $query->skip($skip)->take($take)->get();

    if ($users->isEmpty()) {
        return $this->responseApi(__('No patients found.'), 404);
    }

    return response()->json([
        'data' => UserResource::collection($users),
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

         $user =  User::create($data);

         return $this->responseApi(__('patient created successfully'),$user,200);
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $user = User::where('id',$id)
      ->where('user_type',3)
       ->first();

       if (!$user)
        {
        return $this->responseApi(__('patient not found'), 404);
       }

        return new UserResource($user);

    }

    
    /**
     * Update the specified resource in storage.
     */
    public function updatename(Updatebyname $request, $id)
{
    $request->validated();

    $types = $request->input('user_type');

    $user = User::where('user_type',$types)
    ->where('id', $id)
    ->whereNull('deleted_at')
    ->first();

    if (!$user) 
    {
        return $this->responseApi(__('patient not found'), 404);
    }

    $user->name = $request->name;
    $user->save();

    return $this->responseApi(__('patient name updated successfully'), 200);
}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request,$id)
    {
        $types = $request->input('user_type');
    
         $user = User::where('user_type',$types)
         ->where('id', $id)
         ->first();
    
         if(!$user)
         {
            return $this->responseApi(__('no patient is find'), 404);
         }
    
       $user->delete();
    
         return $this->responseApi(__('patient delete successfully'),200);
    }
}

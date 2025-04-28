<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\UpdateAdmin;
use App\Http\Requests\Api\admin\Updatebyname;
use App\Http\Requests\Api\front\user\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Traits\Common;
use App\Http\Resources\AdminResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use Common;
    use Response;

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

       $user = User::where('email',$data['email'])
       ->where('is_verified',true)
       ->whereNull('deleted_at')
       ->first();

       if(!$user || !Hash::check($data['password'],$user->password ) )
       {
        return $this->responseApi(__('invalid credintials'));
       }

    if ($user->is_verified !== 1) 
    {
        return $this->responseApi(__('Please verify your email first'));
    }

       $user->tokens()->delete();

       $token = $user->createToken('auth_token')->plainTextToken;

       return $this->responseApi(__('login successfully'),$token,200,new UserResource($user));
   
    }

//logout 
    public function logout(Request $request)
    {
        $logout = $request->input('logout');

        if($logout == 0 || !$logout)
        {
            $request->user()->currentAccessToken()->delete();
            return $this->responseApi(__('user logout successfully from current device'));
        }
        elseif($logout == 1)
        {
            $request->user()->tokens()->delete();
            return $this->responseApi(__('user logout successfully from all devices'));
        }  
            
    }

//update data of admin
public function update(UpdateAdmin $request)
{
  $data = $request->validated();

  $data['password']= Hash::make($data['password']);

  $user = auth()->user();

  if($user->user_type !== 1)
{
    return $this->responseApi(__('admin only can be change his data')); 
}

  $user->update($data);

  return new AdminResource($user);

}


//doctors
public function doctors(Request $request)
{
    $search = $request->input('search', null);

    if ($search) {
       
        $user = User::where('user_type', 2)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('mobile', 'like', '%' . $search . '%');
            })
            ->first(); 

        if ($user) 
        {
            return new UserResource($user); 
        }  
        return $this->responseApi(__('No user found with  name, email, or phone'), 404);
    }
 
    $users = User::where('user_type', 2)
        ->paginate(10); 

    return UserResource::collection($users); 
}

//show all patients
public function patients(Request $request)
{
    $search = $request->input('search', null);

    if ($search) 
    {  
        $user = User::where('user_type', 3)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('mobile', 'like', '%' . $search . '%');
            })
            ->first(); 

        if ($user)
         {
            return new UserResource($user); 
        }
 
        return $this->responseApi(__('No user found with  name, email, or phone'), 404);
    }

    $users = User::where('user_type', 3)
        ->paginate(10); 

    return UserResource::collection($users);

}

//update user-name
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
        return $this->responseApi(__('user not found'), 404);
    }

    $user->name = $request->name;
    $user->save();

    return $this->responseApi(__('User name updated successfully'), 200);
}



//delete users
public function delete(Request $request)
{
    if (auth()->user()->user_type !== 1)
     {
        return $this->responseApi(__('Unauthorized,only admin delete users.'), 403);
    }

    $types = $request->input('user_type');

     $user = User::where('user_type',$types)->first();

     if(!$user)
     {
        return $this->responseApi(__('no user is find'), 404);
     }

   $user->delete();

     return $this->responseApi(__('user delete successfully'),200);
}





}

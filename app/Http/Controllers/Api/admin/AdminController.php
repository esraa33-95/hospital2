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
       ->where('user_type', 1)
       ->first();

       if(!$user || !Hash::check($data['password'],$user->password ) )
       {
        return $this->responseApi(__('invalid credintials'));
       }

       if (!$user->is_verified) {
        return response()->json(['message' => 'Please verify your email first.'], 403);
    }

       $token = $user->createToken('auth_token')->plainTextToken;

       return $this->responseApi(__('login successfully'),$token,200,new UserResource($user));
   
    }

//logout 
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

return response()->json(['message' => 'Admin logged out']); 
        
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









}

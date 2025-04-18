<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\Common;
use App\Http\Trait\Response;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\ChangeUserData;


class AdminController extends Controller
{
    use Common;
    use Response;
    //login
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

       $user = User::where('email',$data['email'])->first();

       if(!$user || !Hash::check($data['password'],$user->password ) )
       {
        return $this->responseApi(__('invalid mail or password'));
       }

       $Otp = $user->otps()
        ->where('is_verified', true)
        ->latest()
        ->first();

    if (!$Otp) {
        return $this->responseApi(__('Please verify your email first'), 403);
    }
       $token = $user->createToken('auth_token')->plainTextToken;

       return $this->responseApi(__('login successfully'),$token);
   
    }

    //logout
    public function logout()
    {
        $user = auth()->user();
      
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    
      return $this->responseApi(__('admin logout successfully from all devices'),200);
        
    }

//change data

public function changedata(ChangeUserData $request)
{
  $data = $request->validated();

if ($request->hasFile('image'))
{
   $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
}

$data['password']= bcrypt($data['password']);

$user = User::first();

$user->update($data);

return $this->responseApi(__('admin change data succesfully'),$user,200);

}




}

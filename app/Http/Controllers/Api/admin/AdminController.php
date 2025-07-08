<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\UpdateAdmin;
use App\Http\Requests\Api\front\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Traits\Common;
use App\Models\User;
use App\Traits\Response;
use App\Transformers\admin\UserTransform;
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
        return $this->responseApi(__('messages.invalid credintials'));
       }

       $token = $user->createToken('auth_token')->plainTextToken;

       $admin = fractal()
                 ->item($user)
                 ->transformWith(new UserTransform())
                 ->toArray();

          return $this->responseApi(__('messages.login'),$admin,200,['token'=>$token]);
       
    }

//logout 
public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return $this->responseApi(__('messages.logout_one'));
        
}

//update data of admin
public function update(UpdateAdmin $request)
{
  $user = auth()->user();

//   if($user->user_type !== 1)
// {
//     return $this->responseApi(__('messages.admin')); 
// }

$data = $request->validated();

if (!empty($data['email']) && $data['email']  !== $user->email) 
{
  $user->email = $data['email'];
}

if (!empty($data['password'])  && !Hash::check($data['password'] , $user->password))
 {
  $user->password = Hash::make($data['password']);
 }

  $user->save();

  $admin = fractal()
        ->item($user)
        ->transformWith(new UserTransform())
        ->toArray();

   return $this->responseApi('',$admin,200);

}









}

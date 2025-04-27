<?php

namespace App\Http\Controllers\Api\front\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\User\ChangePassword;
use App\Http\Requests\Api\front\User\updateUser;
use App\Http\Resources\UserResource;
use App\Http\Trait\Response;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Common;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use Common;
    use Response;


    //show profile 
    public function userprofile()
    {
       $user = auth()->user(); 
       return new UserResource($user);
          
    }

//update data
    public function update(updateUser $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assets/images'); 
        }

       $user = auth()->user();

       $user->update($data);

    return new UserResource($user);

 }

//delete account

 public function deleteAccount(Request $request)
{
   $user = auth()->user()->delete();
   return $this->responseApi(__('account delete successufully'));
 
      
}

//change password 
public function changePassword(ChangePassword $request)
{
    $data = $request->validated(); 

    $user = $request->user();

    if (!Hash::check($data['current_password'], $user->password)) 
    {
        return $this->responseApi(__('Current password is incorrect'), 422);
    }

    if (Hash::check($data['new_password'], $user->password)) 
    {
        return $this->responseApi(__('New password must be different from the current password'), 422);
    }

    $user->password = bcrypt($data['new_password']);
    $user->save();

    return $this->responseApi(__('change password successfully'),200);
}

}

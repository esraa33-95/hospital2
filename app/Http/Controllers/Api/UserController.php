<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangeUserPassword;
use App\Http\Requests\Api\UpdateUserData;
use App\Http\Resources\UserResource;
use App\Http\Trait\Response;
use Illuminate\Http\Request;
use App\Traits\Common;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use Common;
    use Response;

    //show profile 
    public function userprofile(Request $request)
    {
       $user = $request->user()->load('department');
       return new UserResource($user);
          
    }

//update data
    public function update(UpdateUserData $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
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
public function changePassword(ChangeUserPassword $request)
{
    $request->validated();
  
    $user = $request->user();
  
    if (!Hash::check($request->current_password, $user->password)) {
        return $this->responseApi(__('Current password is incorrect'));
    }

    if (Hash::check($request->new_password, $user->password)) {
        return $this->responseApi(__('New password must be different from the current password'));
    }
    
    $user->password = bcrypt($request->new_password);
    $user->save();

    return new UserResource($user);
}






}



<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\front\ChangePassword;
use App\Http\Requests\Api\front\updateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Common;
use App\Traits\Response;
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

    //upload image
    public function uploadimage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assets/images'); 
        }

        return $this->responseApi(__('image upload successufully'));
    }

//update data
    public function update(updateUser $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assets/images'); 
        }

        $types = [2,3];
  
       $user = User::withTrashed()
       ->whereIn('user_type', $types)
       ->find(auth()->id());

       if (!$user) 
       {
        return $this->responseApi(__('user not found'), 403);
       }

       if ($user->trashed()) 
       {
        return $this->responseApi(__('Account has been deleted'), 403);
       }

       if(isset($data['name']) && $user->name === $data['name'])
       {
        return $this->responseApi(__('new user is same old name'));
       }

       if(isset($data['email']) && $user->email === $data['email'])
       {
        return $this->responseApi(__('email is same old email'));
       }

       if(isset($data['mobile']) && $user->mobile === $data['mobile'])
       {
        return $this->responseApi(__('mobile is same old mobile'));
       }

       if(isset($data['image']) && $data['image'] === $user->image)
       {
        return $this->responseApi(__('email is same old image'));
       }

       $user->update($data);

    return new UserResource($user);
 }

//delete account

 public function deleteAccount(Request $request)
{
   $user = auth()->user()->delete();

    if (!$user) 
    {
        return $this->responseApi(__('User not authenticated'), 401);
    }

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

    $user->password = Hash::make($data['new_password']);
    $user->save();

    return $this->responseApi(__('change password successfully'),200);
}

}


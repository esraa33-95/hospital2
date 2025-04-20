<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\Common;
use App\Http\Trait\Response;
use App\Http\Requests\Api\ChangeUserData;
use App\Http\Requests\Api\UpdateAdminRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\UserResource;
use App\Models\Department;

class AdminController extends Controller
{
    use Common;
    use Response;


    //assign role for user
    public function assignRole(Request $request, $id)
 {
    $request->validate([
        'role' => 'required|exists:roles,name',
    ]);

    $user = User::findOrFail($id);

    $user->assignRole($request->role);

    return response()->json([
        'message' => 'Role assigned successfully',
    ]);
}
//change data for users

public function changedata(ChangeUserData $request)
{
  $data = $request->validated();

if ($request->hasFile('image'))
{
   $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
}

$data['password']= Hash::make($data['password']);

$user = auth()->user();

$user->update($data);

return new UserResource($user);

}

//update data of admin
public function update(UpdateAdminRequest $request)
{
  $data = $request->validated();

  $data['password']= Hash::make($data['password']);

  $user = auth()->user();

  if ($user->role !== 'admin') 
  {
    return $this->responseApi(__('unauthorized action'),401);
  }

  $user->update($data);

  return new UserResource($user);

}

//show all departments
public function departments()
{
    $user = auth()->user();

    if ($user->role !== 'admin')
     {
      return $this->responseApi(__('unauthorized action'),401);
    }

    $departments = Department::get();

   return  DepartmentResource::collection($departments);
}


}

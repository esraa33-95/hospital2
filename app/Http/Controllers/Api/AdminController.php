<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Traits\Common;
use App\Http\Trait\Response;
use App\Http\Requests\Api\ChangeUserData;
use App\Http\Requests\Api\UpdateAdminRequest;
use App\Http\Requests\Api\CreateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\UserResource;
use App\Models\Department;

class AdminController extends Controller
{
    use Common;
    use Response;

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

  $user->update($data);

  return new UserResource($user);

}


//create departments
public function createdepartments(CreateDepartmentRequest $request)
{
  $data = $request->validated();

$department = Department::create($data);

return  $this->responseApi(__('create department succefully'),$department,200);

}

//show all departments
public function departments()
{
    $departments = Department::get();

   return  DepartmentResource::collection($departments);
}

}

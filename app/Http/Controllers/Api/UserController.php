<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use App\Traits\Common;
class UserController extends Controller
{
    use Common;

    public function show($id)
    {
        $user = User::where('id',$id)
        ->whereIn('role',['patient','doctor'])
        ->firstOrFail();
    
            return response()->json([
                'msg' => 'user profile',
                'data' => $user,
                'status' => 200,
            ]);
          
    }


    public function update(Request $request, string $id)
    {
        $data = $request->validate([
        'name'=>'nullable|string|min:3,max:255',
        'email' => 'nullable|email|unique:users,email',
        'mobile' => [ 'nullable', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,mobile' ],
        'image'=>'nullable|mimes:png,jpg,jpeg',
        'password'=>'nullable|min:6',
        'role'=>'nullable|string',
        'department_id'=>'nullable|exists:departments,id',

        ]);

        if ($request->hasFile('image'))
        {
           $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
        }

    $user = User::where('id',$id)
    ->whereIn('role',['patient','doctor'])
    ->firstOrFail();

    $user->update($data);

    return response()->json([
        'msg' => 'user updated successfully',
        'data' => $user,
        'status' => 200,
    ]);
 }


 public function deleteAccount(Request $request)
{
    auth()->user()->delete();

    return response()->json([
        'msg' => 'Account deleted successfully',
        'status' => 200,
    ]);
}





}

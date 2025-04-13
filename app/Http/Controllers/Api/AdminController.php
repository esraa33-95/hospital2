<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\Common;



class AdminController extends Controller
{
    use Common;
    //login
    public function login(Request $request)
    {
        $data = $request->validate([
         'email' => 'required|email|exists:users',
         'password'=>'required|min:6',
        ]);

       $user = User::where('email',$data['email'])->first();

       if(!$user || !Hash::check($data['password'],$user->password))
       {

        return response([
            'msg'=>'invaild data',
          ]);

        
       }
       $token = $user->createToken('auth_token')->plainTextToken;

       return response()->json([
      'user' => $user,
      'token' => $token,
      'msg'=>'you are logging succesfully',
       ]);
       
    }

    //logout
    public function logout()
    {
        $user = auth()->user();
    
        if ($user) {
            
            $user->currentAccessToken()->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'admin logged out successfully',
                'data' => [],
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'No authenticated admin found',
            'data' => [],
        ], 401);
    }

//change data

public function changedata(Request $request,$id)
{
 $user = User::find($id);

  $data = $request->validate([
      'name'=>'required|string',
      'email' => 'required|email|unique:users,email,' . $id,
      'mobile' => [ 'required', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,mobile,' . $id ],
      'image'=>'nullable|mimes:png,jpg,jpeg',
]);

if ($request->hasFile('image'))
{
   $data['image'] = $this->uploadFile($request->file('image'), 'assests/images'); 
}

$user->update($data);

return response()->json([
    'status' => 200,
    'msg' => 'Data updated successfully',
]);


}




}

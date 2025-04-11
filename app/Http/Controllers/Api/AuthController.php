<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Common;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use Common;

   //register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'image' =>'nullable|mimes:png,jpg,jpeg',
            'mobile' => ['required', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,mobile'],

        ]);
        if($request->hasfile('image'))
        {
           $data['image'] = $this->uploadFile($request->image,'assets/images');
        }

       $user = User::create($data);

       return $user;

    }


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
            'msg'=>'invaid',
          ]);

        
       }
       $token = $user->createToken('auth_token')->plainTextToken;

       return response()->json([
      'user' => $user,
      'token' => $token,
      'msg'=>'you are logging succesfully',
       ]);
       
    }

    public function logout()
    {
        $user = auth()->user();
    
        if ($user) {
            
            $user->currentAccessToken()->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'User logged out successfully',
                'data' => [],
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'No authenticated user found',
            'data' => [],
        ], 401);
    }
    

}

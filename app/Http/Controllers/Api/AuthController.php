<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Common;
use App\Models\User;

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


    


}

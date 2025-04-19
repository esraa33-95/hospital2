<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\EmailOtpMail;
use App\Models\Otp;
use Illuminate\Http\Request;
use App\Traits\Common;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Trait\Response;
use App\Events\UserRegistered;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\VerifyEmailOtp;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\ForgetPassword;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    use Response;
    use Common;

   //register
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        if($request->hasfile('image'))
        {
           $data['image'] = $this->uploadFile($request->image,'assets/images');
        }
        
        $data['password'] = Hash::make($data['password']);

       $user = User::create($data);

       event(new UserRegistered($user));

    return $this->responseApi(__('registered successfully'),$user,201);
    }


//login
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

       $user = User::where('email',$data['email'])->first();

       if(!$user || !Hash::check($data['password'],$user->password ) )
       {
        return $this->responseApi(__('invalid mail or password'));
 
       }

       $Otp = $user->otps()
        ->where('is_verified', true)
        ->latest()
        ->first();

    if (!$Otp) {
        return $this->responseApi(__('Please verify your email first'), 403);
    }
       $token = $user->createToken('auth_token')->plainTextToken;

       return $this->responseApi(__('login successfully'),$token,200);
   
    }

//logout
    public function logout()
    {
        $user = auth()->user();
      
        $user->tokens()->delete();
    
      return $this->responseApi(__('user logout successfully from all devices'),$user,200);
        
    }
    
//forget password
    public function forgetpassword(ForgetPassword $request)
{
    $request->validated();
      
    $user = User::where('email', $request->email)->first();

    if (!$user) 
    {
        return $this->responseApi(__('User not found'),404);
    }

    event(new UserRegistered($user));

    return $this->responseApi(__('send otp to mail successfully'),$user,200);
    
}


//verify mail and reset password using otp
public function verifyEmailOtp(VerifyEmailOtp $request)
{
    $request->validated();

    $user = User::where('email', $request->email)->first();

    $otp = $user->otps()
        ->where('otp', $request->otp)
        ->where('expires_at', '>=', now())
        ->latest()
        ->first();

    if (!$otp) {
        return $this->responseApi(__('invalid otp'),422);
    }

    $otp->update(['is_verified'=>true]);
    $user->update(['email_verified_at' => now()]);

    if ($request->filled('password')) {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return $this->responseApi(__('OTP verified and password reset successfully'), 200);
    }

    return $this->responseApi(__('Otp verified successfully'), 200);
}





}

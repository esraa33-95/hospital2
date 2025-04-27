<?php

namespace App\Http\Controllers\Api\front\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Common;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Trait\Response;
use App\Events\UserRegistered;
use App\Http\Requests\Api\front\user\ForgetPassword;
use App\Http\Requests\Api\front\user\LoginRequest;
use App\Http\Requests\Api\front\user\RegisterRequest;
use App\Http\Requests\Api\front\user\ResetPassword;
use App\Http\Requests\Api\front\user\SendOtp;
use App\Http\Requests\Api\front\user\VerifyEmailOtp;
use App\Http\Resources\UserResource;
use App\Mail\EmailOtpMail;
use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

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

       $user = User::where('email',$data['email'])
       ->whereNull('deleted_at')
       ->first();

       if(!$user || !Hash::check($data['password'],$user->password ))
       {
        return $this->responseApi(__('invalid credintials'));
       }

    if ($user->is_verified !== 1) 
    {
        return $this->responseApi(__('Please verify your email first'));
    }
       $token = $user->createToken('auth_token')->plainTextToken;

       return $this->responseApi(__('login successfully'),$token,200,new UserResource($user));
   
    }

//logout if param from user
    public function logout(Request $request)
    {
        $logout = $request->input('logout');

        if($logout == 0 || !$logout)
        {
            $request->user()->currentAccessToken()->delete();
            return $this->responseApi(__('user logout successfully from current device'));
        }
        elseif($logout == 1)
        {
            $request->user()->tokens()->delete();
            return $this->responseApi(__('user logout successfully from all devices'));
        }  
    
         
    }
    //send otp
    public function sendotp(SendOtp $request)
    {
        $request->validated();

        $usage = $request->input('usage');

        $user = User::where('email', $request->email)->first();

        Otp::where('user_id',$user->id)
        ->where('usage',$usage)
        ->delete();

        $otp = rand(1000, 9999);

        $otp = Otp::create([
           'user_id'=> $user->id,
           'otp'=> $otp,
           'epires_at'=> Carbon::now()->addMinutes(3),
           'usage'=>$usage,
        ]);

        Mail::to('esraahedia33@gmail.com')->send(new EmailOtpMail($otp,$usage));
        
        return $this->responseApi(__(' code Otp sent to mail '), 200);
    }


//verify mail 
public function verifyEmailOtp(VerifyEmailOtp $request)
{
   $request->validated();

    $user = User::where('email', $request->email)->first();

    $otp = $user->otps()
    ->where('otp', $request->otp)
    ->where('expires_at','>=',now())
    ->first();

    if(!$otp)
    {
        return $this->responseApi(__('invalid otp'),400);   
    }

    if($request->usage === 'verify')
    {
        $user->update(['is_verified'=>true]);
        $user->save();

        $otp->delete();
     
         return $this->responseApi(__('Otp verified successfully'), 200);
    }

    return $this->responseApi(__('not allowed to use this type'), 403); 
}


//reset password
public function resetpassword(ResetPassword $request)
{
    $request->validated();

    if ($request->usage !== 'forget') {
        return $this->responseApi(__('not allowed to use this otp'), 403);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return $this->responseApi(__('User not found'), 404);
    }

    $otp = Otp::where('user_id', $user->id)
        ->where('otp', $request->otp)
        ->where('expires_at', '>=', now())
        ->first();

    if (!$otp) {
        return $this->responseApi(__('Invalid or expired OTP'), 404);
    }

    if (!Hash::check($request->old_password, $user->password)) {
        return $this->responseApi(__('Current password is incorrect'), 422);
    }

    if (Hash::check($request->new_password, $user->password)) {
        return $this->responseApi(__('current password is same as new password can you continue'));
    }

    $user->password = bcrypt($request->new_password);
    $user->save();

    $user->tokens()->delete();
  
    return $this->responseApi(__('Password changed successfully'), 200);
}


}
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

       return $this->responseApi(__('login successfully'),$token);
   
    }

//logout
    public function logout()
    {
        $user = auth()->user();
      
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    
      return $this->responseApi(__('user logout successfully from all devices'),200);
        
    }
    
//forget password
    public function sendPasswordEmail(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users']);

    $status = Password::sendResetLink($request->only('email'));

    if ($status === Password::RESET_LINK_SENT) {
        return response()->json([
            'message' => __('Password reset link  successfully.')
        ], 200);
    } 

    return response()->json([
        'message' => __('Error sending reset link.'),
        'error' => __($status) 
    ], 400);
}


//reset link to change password
public function reset(Request $request)
{
    $request->validate([
        'token'=>'required',
        'email' => 'required|email|exists:users',
        'password'=>'required|min:6|confirmed',
    ]);

       $status = Password::reset(
        $request->only('email','password','password_confirmation','token'),
        function(User $user, string $password)
        {
            $user->forceFill([
                'password' => Hash::make($password) ,
                 'remember_token'=>Str::random(60)
            ])->save(); 
        }
    );
    if ($status === Password::PASSWORD_RESET) {
        return response()->json([
            'message' => __('Password  reset successfully.')
        ], 200);
    } 

    return response()->json([
        'message' => __('Invalid '),
        'error' => __($status) 
    ], 400);
}


//verify mail using otp
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

    return $this->responseApi(__('email verified successfully'),200);
}





}

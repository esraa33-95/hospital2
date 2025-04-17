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




class AuthController extends Controller
{
    use Common;

   //register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'image' =>'nullable|mimes:png,jpg,jpeg',
            'mobile' => ['required', 'regex:/^01[0125][0-9]{8}$/', 'unique:users,mobile'],
            'department_id' => 'required|exists:departments,id',
            'role'=>'required|string',
        ]);
        if($request->hasfile('image'))
        {
           $data['image'] = $this->uploadFile($request->image,'assets/images');
        }
        
        $data['password'] = Hash::make($data['password']);

       $user = User::create($data);

    //    $otp = rand(100000, 999999);
        
    //     Otp::create([
    //         'user_id' => $user->id,
    //         'otp' => $otp,
    //         'expires_at' => now()->addMinutes(3),
    //     ]);
     
    //    Mail::to($user->email)->send(new EmailOtpMail($otp));


       return response()->json([
        'message' => 'Registered successfully',
        'user' => $user
    ], 201);
    }

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
            'msg'=>'invaid email or password',
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

//sendotp for mail
// public function sendEmailOtp(Request $request)
// {
//     $request->validate([
//         'email' => 'required|email|exists:users,email',
//     ]);

//     $user = User::where('email', $request->email)->first();
    
//     $otp = rand(100000, 999999);
 
//     Otp::create([
//         'user_id' => $user->id,
//         'otp' => $otp,
//         'expires_at' => now()->addMinutes(10),
//     ]);

   
//     Mail::to($user->email)->send(new EmailOtpMail($otp));

//     return response()->json([
//         'status' => 200,
//         'message' => 'OTP sent successfully.',
//     ]);
// }


//verify mail using otp
public function verifyEmailOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required|digits:6',
    ]);

    $user = User::where('email', $request->email)->first();

    $otp = $user->otps()
        ->where('otp', $request->otp)
        ->where('is_used', false)
        ->where('expires_at', '>=', now())
        ->latest()
        ->first();

    if (!$otp) {
        return response()->json([
            'status' => 401,
            'message' => 'invalid otp.',
        ], 401);
    }

    $otp->update(['is_used' => true]);
    $user->update(['email_verified_at' => now()]);

    return response()->json([
        'status' => 200,
        'message' => 'Email verified successfully.',
    ]);
}





}

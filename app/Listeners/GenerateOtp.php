<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Otp;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailOtpMail;

class GenerateOtp
{
    /**
     * Create the event listener.
     */
    

public function __construct()
{
   
}

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
{
    
    $otpCode = Otp::generateCode();

    Otp::create([
        'user_id' => $event->user->id,
        'otp' => $otpCode,
        'expires_at' => now()->addMinutes(3),
    ]);

    Mail::to($event->user->email)->send(new EmailOtpMail($otpCode));
}

}

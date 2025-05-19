<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\Otp;


class GenerateOtp
{
    /**
     * Create the event listener.
     */
    

public function __construct()
{
   
}

    public function handle(UserRegistered $event): void
{
    $otpCode = Otp::generateCode();

    Otp::create([
        'user_id' => $event->user->id,
        'otp' => $otpCode,
        'expires_at' => now()->addMinutes(3),
        
    ]);
    

}

}
<?php

namespace App\Notifications;

use App\Models\ApiEmailVerification as ModelsApiEmailVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class apiEmailVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
{
    $user_id = $notifiable->id;
    $vcode = mt_rand(11111, 99999);

    // Save code to DB using the correct model
    ApiEmailVerification::updateOrCreate(
        ['user_id' => $user_id],
        ['evcode' => $vcode]
    );

    return (new MailMessage())
        ->subject('Your Verification Code')
        ->line('Here is your verification code:')
        ->line($vcode)
        ->action('Verify Email', url('/your-url')) // optional
        ->line('Thank you!');
}


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

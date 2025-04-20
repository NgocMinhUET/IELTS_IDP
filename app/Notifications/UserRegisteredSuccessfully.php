<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Enum\Auth;

class UserRegisteredSuccessfully extends Notification
{
    use Queueable;

    protected $otp;

    /**
     * Create a new notification instance.
     *
     * @param $otp
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param object $notifiable
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('AIMM -アカウントを確認する')
            ->view(
                'emails.otp_active_account',
                [
                    'otp' => $this->otp,
                    'time_expired' => Auth::REGISTER_OTP_EXPIRE->value . '分間',
                ]
            );
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

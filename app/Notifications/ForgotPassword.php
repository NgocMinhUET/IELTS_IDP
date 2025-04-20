<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use App\Enum\Auth;

class ForgotPassword extends Notification
{
    use Queueable;
    protected array $data;

    /**
     * Create a new notification instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
     * @param object $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = Arr::get($this->data, 'otp');

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('AIMM -パスワードをお忘れですか')
            ->view(
                'emails.otp_forgot_password',
                [
                    'otp' => $otp,
                    'time_expired' => Auth::RESET_PASSWORD_EXPIRE->value . '時間',
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

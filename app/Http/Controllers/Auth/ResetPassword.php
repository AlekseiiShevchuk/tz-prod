<?php
/**
 * Created by PhpStorm.
 * User: mendel
 * Date: 10.03.17
 * Time: 16:01
 */

namespace App\Http\Controllers\Auth;


use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage();
        $mailMessage->view('emails.email');
        $mailMessage->subject(trans('resetmail.subject'));

        return $mailMessage
            ->line(trans('resetmail.hello', ['name' => $notifiable->name]))
            ->line(trans('resetmail.first_line'))
            ->action(trans('resetmail.reset_password'), url('password/reset', $this->token))
            ->line(trans('resetmail.second_line'))
            ->line(trans('resetmail.footer'));
    }
}
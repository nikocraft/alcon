<?php

namespace App\Notifications\Users;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ActivateUserNotification extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = URL::temporarySignedRoute('backend.user.activate.show', now()->addHours(24), ['user' => $this->user->id]);

        return (new MailMessage)
                    ->subject('Activate your user account')
                    ->line('Hi ' . $this->user->firstname . '!')
                    ->line('Administrator at laraone.co has created an account for you.')
                    ->line('To activate your user account and set the password please click the button below.')
                    ->action('Activate Account', $url)
                    ->line('Thank you.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

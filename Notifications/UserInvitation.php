<?php

namespace App\Notifications;

use App\Events\UserInvited;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitation extends Notification
{
    use Queueable;

    protected $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user = null)
    {
        if($this->user == null) {
            $this->user = $user;
        }
    }

    public function handle(UserInvited $event)
    {
        $this->user = $event->user;
        $this->user->notify(new UserInvitation($event->user));
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
        return (new MailMessage)
                    ->line('You are invited to join the team!')
                    ->action('Complete your registeration', route('register', ['invitation' => $this->user->invitation_token]))
                    ->line('Thank you we are looking forword!');
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

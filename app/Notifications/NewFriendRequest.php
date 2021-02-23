<?php

namespace App\Notifications;

use App\Friendship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFriendRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public $friendship;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Friendship $friendship)
    {
        $this->friendship = $friendship;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return 
     */
    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->friendship->id,
        ];
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
            'id' => $this->friendship->id,
            'first_user' => $this->friendship->first_user,
            'second_user' => $this->friendship->second_user,
            'notifiable' => $notifiable
        ];
    }

}

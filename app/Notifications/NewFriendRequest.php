<?php

namespace App\Notifications;

use App\Models\Friendship;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewFriendRequest extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    public Friendship $friendship;

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
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
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
    public function toArray($notifiable): array
    {
        return [
            'id' => $this->friendship->id,
            'first_user' => $this->friendship->first_user,
            'second_user' => $this->friendship->second_user,
            'notifiable' => $notifiable
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->friendship->id,
        ]);

    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('friendRequests.' . $this->friendship->second_user);
    }

    public function broadcastAs(): string
    {
        return NewFriendRequest::class;
    }
}

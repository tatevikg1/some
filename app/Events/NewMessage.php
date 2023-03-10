<?php

namespace App\Events;

use App\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }


    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('messages.' . $this->message->receiver);
    }


    public function broadcastWith(): array
    {
        //loading the contact who sent the message to use it in vue and update(+=1) unread messages count
        $this->message->load('fromContact');

        return ['message' => $this->message ];
    }
}

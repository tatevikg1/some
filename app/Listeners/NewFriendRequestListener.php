<?php

namespace App\Listeners;

use App\Events\NewFriendRequestEvent;
use App\Notifications\NewFriendRequest;
use App\User;

class NewFriendRequestListener
{
    /**
     * Handle the event.
     *
     * @param NewFriendRequestEvent $event
     * @return void
     */
    public function handle(NewFriendRequestEvent $event)
    {
        $user = User::find($event->friendship->second_user);
        $notification = new NewFriendRequest($event->friendship);
        $user->notify($notification);
        broadcast($notification);
    }
}
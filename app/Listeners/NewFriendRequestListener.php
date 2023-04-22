<?php

namespace App\Listeners;

use App\Events\NewFriendRequestEvent;
use App\Models\User;
use App\Notifications\NewFriendRequest;

class NewFriendRequestListener
{
    /**
     * Handle the event.
     *
     * @param NewFriendRequestEvent $event
     * @return void
     */
    public function handle(NewFriendRequestEvent $event): void
    {
        $user = User::find($event->friendship->second_user);
        $notification = new NewFriendRequest($event->friendship);
        $user->notify($notification);
        broadcast($notification);
    }
}

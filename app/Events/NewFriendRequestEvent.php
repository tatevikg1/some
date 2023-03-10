<?php

namespace App\Events;

use App\Friendship;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewFriendRequestEvent
{
    use Dispatchable, SerializesModels;

    public $friendship;

    public function __construct(Friendship $friendship)
    {
        $this->friendship = $friendship;
    }
}

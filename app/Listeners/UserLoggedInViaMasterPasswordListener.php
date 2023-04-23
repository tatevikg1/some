<?php

namespace App\Listeners;

use App\Constants\AppConstants;
use App\Events\UserLoggedInViaMasterPasswordEvent;
use Illuminate\Support\Facades\Log;

class UserLoggedInViaMasterPasswordListener
{
    public function handle(UserLoggedInViaMasterPasswordEvent $event): void
    {
        Log::channel(AppConstants::LOGIN)->info(json_encode([
            'user' => $event->getUserId(),
            'ip_address' => $event->getUserIp(),
            'time' => $event->getTime(),
        ]));
    }
}

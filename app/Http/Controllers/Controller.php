<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * marks user notifications as read 
     * @param string $notificationClassName
     * @return bool
     * */ 
    public function markAsRead($notificationName)
    {
        // mark read new friend request notifications of user
        $user = auth()->user();
        $user->unreadNotifications
            ->where('type', $notificationName)
            ->markAsRead();
        return true;
    }

}

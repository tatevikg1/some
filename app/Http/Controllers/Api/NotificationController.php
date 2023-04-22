<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewFriendRequest;
use App\Notifications\NewMessage;

class NotificationController extends Controller
{
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications->where('type', NewMessage::class)->count();
    }

    public function getFriendNotification(User $user)
    {
        return $user->unreadNotifications->where('type', NewFriendRequest::class)->count();
    }
}

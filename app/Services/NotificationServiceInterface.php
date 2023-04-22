<?php

namespace App\Services;

interface NotificationServiceInterface
{
    public function storeKey(array $data);

    public function sendNotification(array $notificationData);
}

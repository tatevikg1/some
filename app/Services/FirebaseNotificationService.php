<?php

namespace App\Services;

use App\Constants\AppConstants;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kutia\Larafirebase\Facades\Larafirebase;

class FirebaseNotificationService implements NotificationServiceInterface
{
    private const ACTION_STORE_KEY = 'store_device_key';
    private const ACTION_SEND_NOTIFICATION = 'send_notification';
    private const CHUNK_SIZE = 20;

    public function storeKey(array $data): void
    {
        User::where('id', auth()->user()->getAuthIdentifier())->update([
            'device_key' => $data['token']
        ]);
        Log::channel(AppConstants::PUSH_NOTIFICATIONS)->info(json_encode([
            'user' => auth()->user()->getAuthIdentifier(),
            'action' => self::ACTION_STORE_KEY,
            'device_key' => $data['token'],
        ]));
    }

    public function sendNotification(array $notificationData)
    {
        $count = 0;
        User::whereNotNull('device_key')->chunkById(self::CHUNK_SIZE, function ($fcmTokens) use ($notificationData, &$count) {
            $tokensAsArray = $fcmTokens->pluck('device_key')->toArray();
            Larafirebase::withTitle($notificationData['title'])
                ->withBody($notificationData['body'])
                ->sendMessage($tokensAsArray);
//        auth()->user()->notify(new SendPushNotification($notificationData['title'], $notificationData['body'], $fcmTokens));
            $count += count($fcmTokens);
            Log::channel(AppConstants::PUSH_NOTIFICATIONS)->info(json_encode([
                'user' => auth()->user()->getAuthIdentifier(),
                'action' => self::ACTION_SEND_NOTIFICATION,
                'data' => [
                    "registration_ids" => $tokensAsArray,
                    "notification" => [
                        "title" => $notificationData['title'],
                        "body" => $notificationData['body'],
                    ]
                ],
            ]));
        });

        return $count;
    }
}

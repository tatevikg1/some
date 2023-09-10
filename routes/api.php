<?php

declare(strict_types=1);

use App\Http\Controllers\Api\FriendsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/get-like-count/{post}', [PostController::class, 'getLikeCount']);
Route::get('/get-likes/{post}/{user}', [PostController::class, 'isLikedByUser']);

Route::get('/get-friend-request-notification/{user}', [NotificationController::class, 'getFriendNotification']);
Route::get('/get-message-notification/{user}', [NotificationController::class, 'getUnreadCount']);

Route::post('/get-random-4-friend/{user}', [FriendsController::class, 'getRandomFour']);

Route::post('/message', function (Request $request) {
        $message = $request->get('message');
        $mqService = new \App\Services\RabbitMQService();
        $mqService->publish(\App\Jobs\RabbitMQ\SentTestRabbitMQJob::JOB_KEY, ['message' => $message]);
        return 'Success';
});

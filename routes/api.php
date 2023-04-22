<?php

use App\Http\Controllers\Api\FriendsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-like-count/{post}', [PostController::class, 'getLikeCount']);
Route::get('/get-likes/{post}/{user}', [PostController::class, 'isLikedByUser']);

Route::get('/get-friend-request-notification/{user}', [NotificationController::class, 'getFriendNotification']);
Route::get('/get-message-notification/{user}', [NotificationController::class, 'getUnreadCount']);

Route::post('/get-random-4-friend/{user}', [FriendsController::class, 'getRandomFour']);

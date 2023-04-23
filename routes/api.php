<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\FriendsController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/login/2fa', [AuthenticationController::class, 'twoFactorLogin']);
Route::post('/forgotPassword', [AuthenticationController::class, 'forgotPassword']);
Route::get('/getSocialLoginCredentials', [AuthenticationController::class, 'getSocialLoginCredentials']);

Route::middleware('auth:api')->group(function() {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/google2faGenerateQrCode', [UserController::class, 'google2faGenerateQrCode']);
    Route::post('enable', [UserController::class, 'google2faEnable']);
    Route::post('disable', [UserController::class, 'google2faDisable']);
});

Route::get('/get-like-count/{post}', [PostController::class, 'getLikeCount']);
Route::get('/get-likes/{post}/{user}', [PostController::class, 'isLikedByUser']);

Route::get('/get-friend-request-notification/{user}', [NotificationController::class, 'getFriendNotification']);
Route::get('/get-message-notification/{user}', [NotificationController::class, 'getUnreadCount']);

Route::post('/get-random-4-friend/{user}', [FriendsController::class, 'getRandomFour']);

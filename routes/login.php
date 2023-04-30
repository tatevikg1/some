<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\SocialLoginController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/get-social-credentials', [SocialLoginController::class, 'getSocialLoginCredentials']);
Route::post('/auth-social', [SocialLoginController::class, 'authSocial']);
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/login-2fa', [AuthenticationController::class, 'twoFactorLogin']);
Route::post('/forgot-password', [AuthenticationController::class, 'forgotPassword']);

Route::middleware('auth:api')->group(function() {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/google2faGenerateQrCode', [UserController::class, 'google2faGenerateQrCode']);
    Route::post('enable', [UserController::class, 'google2faEnable']);
    Route::post('disable', [UserController::class, 'google2faDisable']);
});

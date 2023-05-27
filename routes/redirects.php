<?php

use App\Http\Controllers\Api\SocialLoginController;
use Illuminate\Support\Facades\Route;


Route::get('auth/redirect', [SocialLoginController::class, 'redirect']);


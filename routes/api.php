<?php

use App\Message;
use App\Post;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-like-count/{post}', function(Post $post){
    return $post->likers->count();
});

Route::get('/get-likes/{post}/{user}', function(Post $post, User $user){
    return  ($user->id) ? $user->liking->contains($post->id) : 0;
});

Route::get('/get-friend-request-count/{user}', function(User $user){
    return $user->unreadNotifications->where('type', 'App\Notifications\NewFriendRequest')->count();
});

Route::get('/get-unread-message-count/{user}', function(User $user){
    return $user->unreadNotifications->where('type', 'App\Notifications\NewMessage')->count();
});

Route::post('/get-random-4-friend/{user}', function(User $user){
    $friends = $user->friends->shuffle()->take(6);

    foreach($friends as $f){
        $f->profile = Profile::where('user_id', $f->id)->first();
    }

    return $friends;
});
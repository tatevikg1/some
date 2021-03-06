<?php

use App\Post;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-like-count/{post}', function(Post $post){
    return $post->likers->count();
});

Route::get('/get-likes/{post}/{user}', function(Post $post, User $user){
    return  ($user->id) ? $user->liking->contains($post->id) : 0;
});

Route::get('/get-friend-request-notification/{user}', function(User $user){
    return $user->unreadNotifications->where('type', 'App\Notifications\NewFriendRequest')->count();
});

Route::get('/get-message-notification/{user}', function(User $user){
    return $user->unreadNotifications->where('type', 'App\Notifications\NewMessage')->count();
});

Route::post('/get-random-4-friend/{user}', function(User $user){
    $friends = $user->friends->shuffle()->take(6);

    foreach($friends as $f){
        $f->profile = Profile::where('user_id', $f->id)->first();
    }

    return $friends;
});
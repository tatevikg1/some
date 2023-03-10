<?php

use App\Http\Controllers\ChatsController;
use App\Http\Controllers\FollowsController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ProfilesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();
// Route::get('/email', function () { return new NewUserWelcomeMail(); });

// follow and like buttons routes
Route::post('follow/{user}',    [FollowsController::class, 'store']);
Route::post('like/{post}',      [LikesController::class, 'store']);

// friendship system routes
Route::post('add-friend/{user}',        [FriendsController::class, 'send_friend_request']);
Route::post('confirm/{friendship}',     [FriendsController::class, 'confirm_friend_request']);
Route::post('delete/{friendship}',      [FriendsController::class, 'delete_friend_request']);
// Route::post('block/{user}',      'FriendController@block');
Route::get  ('/friend',              [FriendsController::class, 'index'])->name('friend.index');
Route::post ('/friend',              [FriendsController::class, 'markAsRead']);

// post routes
Route::get   ('/',                 [PostsController::class, 'index'])->name('post.index');
Route::get   ('/post/create',      [PostsController::class, 'create'])->name('post.create')->middleware('auth');
Route::post  ('/post',             [PostsController::class, 'store'])->name('post.store')->middleware('auth');
Route::get   ('/post/{post}',      [PostsController::class, 'show'])->name('post.show');
Route::delete('/post/{post}',      [PostsController::class, 'destroy'])->name('post.destroy')->middleware('auth');
Route::get   ('/post',             [PostsController::class, 'liked'])->name('post.liked')->middleware('auth');

// profile
Route::get  ('/profile',             [ProfilesController::class, 'index'])->name('profile.index');
Route::get  ('/profile/{user}',      [ProfilesController::class, 'show'])->name('profile.show');
Route::get  ('/profile/{user}/edit', [ProfilesController::class, 'edit'])->name('profile.edit');
Route::patch('/profile/{user}',      [ProfilesController::class, 'update'])->name('profile.update');
Route::delete('/profile/{user}',     [ProfilesController::class, 'destroy'])->name('profile.destroy');
Route::post ('/profile/find',        [ProfilesController::class, 'find'])->name('profile.find');

// routes for the chat part of the app
Route::get  ('/chats',              [ChatsController::class, 'chat'])->name('chat');
Route::post ('/chat/mark-as-read/', [ChatsController::class, 'markAsRead']);
Route::post ('/messages/{id}',      [ChatsController::class, 'setRead']);
Route::post ('/contacts',           [ChatsController::class, 'contacts']);
Route::get  ('/conversation/{id}',  [ChatsController::class, 'getMessagesWithContact']);
Route::post ('/conversation/send',  [ChatsController::class, 'send']);

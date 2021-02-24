<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// DB clean
Route::get('/clean', 'HomeController@index');

Auth::routes();
// Route::get('/email', function () { return new NewUserWelcomeMail(); });

// follow and like buttons routes
Route::post('follow/{user}',    'FollowsController@store');
Route::post('like/{post}',      'LikesController@store');

// friendship system routes
Route::post('addfriend/{user}',         'FriendsController@send_friend_request');
Route::post('confirm/{friendship}',     'FriendsController@confirm_friend_request');
Route::post('delete/{friendship}',      'FriendsController@delete_friend_request');
// Route::post('block/{user}',      'FriendController@block');
Route::get  ('/friend',              'FriendsController@index')     ->name('friend.index');
Route::post ('/friend',              'FriendsController@markAsRead');

// post routes
Route::get   ('/',                 'PostsController@index')  ->name('post.index');
Route::get   ('/popt/create',      'PostsController@create') ->name('post.create')->middleware('auth');
Route::post  ('/post',             'PostsController@store')  ->name('post.store')->middleware('auth');
Route::get   ('/post/{post}',      'PostsController@show')   ->name('post.show');
Route::delete('/post/{post}',      'PostsController@destroy')->name('post.destroy')->middleware('auth');
Route::get   ('/post',             'PostsController@liked')  ->name('post.liked')->middleware('auth');


// profile
Route::get  ('/profile',             'ProfilesController@index')    ->name('profile.index');
Route::get  ('/profile/{user}',      'ProfilesController@show')     ->name('profile.show');
Route::get  ('/profile/{user}/edit', 'ProfilesController@edit')     ->name('profile.edit');
Route::patch('/profile/{user}',      'ProfilesController@update')   ->name('profile.update');
Route::delete('/profile/{user}',     'ProfilesController@destroy')  ->name('profile.destroy');
Route::post ('/profile/find',        'ProfilesController@find')     ->name('profile.find');

// routes for the chat part of the app
Route::get  ('/chats',     function(){ return view('chat_app.chat'); })->name('chat');
Route::post ('/chat/mark-as-read/', 'ChatsController@markAsRead');
Route::post ('/messages/{id}',      'ChatsController@setRead');
Route::post ('/contacts',           'ChatsController@contacts');
Route::get  ('/conversation/{id}',  'ChatsController@getMessagesWithContact');
Route::post ('/conversation/send',  'ChatsController@send');

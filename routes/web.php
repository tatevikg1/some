<?php



use App\Mail\NewUserWelcomeMail;

Auth::routes();

Route::get('/email', function () {
    return new NewUserWelcomeMail();
});

// follow and like buttons routes
Route::post('follow/{user}',    'FollowsController@store');
Route::post('like/{post}',      'LikesController@store');

// post routes
Route::get   ('/',              'PostsController@index');
Route::get   ('/p/create',      'PostsController@create');
Route::post  ('/p',             'PostsController@store');
Route::get   ('/p/{post}',      'PostsController@show');
Route::get('/delete/{post}',    'PostsController@destroy')->name('posts.destroy');

// profile
Route::get  ('/profile',             'ProfilesController@index')->name('profile.index');
Route::get  ('/profile/{user}',      'ProfilesController@show')->name('profile.show');
Route::get  ('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}',      'ProfilesController@update')->name('profile.update');
Route::delete('/profile/{user}',     'ProfilesController@destroy')->name('profile.destroy');
Route::post ('/profile/find',        'ProfilesController@find')->name('profile.find');

// routes for the chat part of the app
Route::get  ('/chats',     function(){
    return view('chat_app.chat');
});
Route::post ('/messages/{id}',      'ChatsController@setRead');
Route::post ('/contacts',           'ChatsController@contacts');
Route::get  ('/conversation/{id}',  'ChatsController@getMessagesWithContact');
Route::post ('/conversation/send',  'ChatsController@send');

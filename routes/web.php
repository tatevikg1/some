<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Mail\NewUserWelcomeMail;

Auth::routes();

Route::get('/email', function () {
    return new NewUserWelcomeMail();
});

Route::post('follow/{user}',    'FollowsController@store');
Route::post('like/{post}',      'LikesController@store');

Route::get   ('/',              'PostsController@index');
Route::get   ('/p/create',      'PostsController@create');
Route::post  ('/p',             'PostsController@store');
Route::get   ('/p/{post}',      'PostsController@show');
Route::get('/delete/{post}',    'PostsController@destroy')->name('posts.destroy');


Route::get  ('/profile',             'ProfilesController@index')->name('profile.index');
Route::get  ('/profile/{user}',      'ProfilesController@show')->name('profile.show');
Route::get  ('/profile/{user}/edit', 'ProfilesController@edit')->name('profile.edit');
Route::patch('/profile/{user}',      'ProfilesController@update')->name('profile.update');
Route::delete('/profile/{user}',     'ProfilesController@destroy')->name('profile.destroy');
Route::post ('/profile/find',        'ProfilesController@find')->name('profile.find');


#Route::get('/m/{user}', 'MessageController@index')->name('messages.index');
Route::get  ('/message/{from}/{to}', 'MessageController@index')->name('messages.index');
Route::post ('/message/{from}/{to}', 'MessageController@store')->name('messages.store');

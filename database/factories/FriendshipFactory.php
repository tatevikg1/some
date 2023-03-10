<?php


/** @var Factory $factory */

use App\Friendship;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
$factory->define(Friendship::class, function (Faker $faker) {
    do{
        $firstUser = rand(1, 1500);
        $secondUser = rand(1, 1500);
    }while($firstUser === $secondUser);

    $actedUser = ($firstUser%2 === 1) ? $firstUser : $secondUser;

    switch($actedUser){
        case $firstUser:
            $status = 'pending';
            break;
        case $secondUser:
            $status = 'confirmed';
            break;
    }
    return [
        'first_user' => $firstUser,
        'second_user' => $secondUser,
        'acted_user' => $actedUser,
        'status' => $status,
    ];
});

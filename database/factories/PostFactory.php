<?php

/** @var Factory $factory */

use App\Models\Post;
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

$factory->define(Post::class, function (Faker $faker) {
    return [
        'user_id' => Str::random(1500),
        'caption' => $faker->sentence,
        'image' => 'profile/profile.jpeg',
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

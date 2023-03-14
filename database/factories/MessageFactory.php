<?php

/** @var Factory $factory */

use App\Models\Message;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;


$factory->define(Message::class, function (Faker $faker) {

    do{
        $from = rand(1, 1500);
        $to = rand(1, 1500);

    }while($from == $to);

    return [
        'sender' => $from,
        'receiver' => $to,
        'text' => $faker->sentence,
    ];
});

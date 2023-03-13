<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        factory(App\User::class, 1500)->create();
//        factory(App\Message::class, 1500000)->create();
//        factory(App\Friendship::class, 15000)->create();
        factory(App\Post::class, 1500000)->create();
    }
}

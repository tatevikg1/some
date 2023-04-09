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
        factory(App\models\User::class, 1500)->create();
        factory(App\Models\Message::class, 1500000)->create();
        factory(App\Models\Friendship::class, 15000)->create();
        factory(\App\Models\Post::class, 1500000)->create();
    }
}

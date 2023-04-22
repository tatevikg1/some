<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        Message::factory()->count(1500000)->create();
        Post::factory()->count(1500000)->create();
        $this->call(FriendshipSeeder::class);
    }
}

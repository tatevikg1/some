<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 100000; $i++)
        {
            Post::create([
                'user_id' => rand(1, 1000),
                'caption' => fake()->word,
                'image' => 'profile/profile.jpeg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

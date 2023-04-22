<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => Str::random(1500),
            'caption' => fake()->sentence,
            'image' => 'profile/profile.jpeg',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

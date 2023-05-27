<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 1000; $i++)
        {
            $user = User::updateOrCreate([
                'username' => Str::random(10),
                'email' => fake()->unique()->safeEmail(),
            ],[
                'name' => fake()->name(),
                'email_verified_at' => now()->toDateString(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token' => Str::random(10),
                'created_at' => now()->toDateString(),
                'updated_at' => now()->toDateString(),
            ]);

            Profile::updateOrInsert([
                'user_id' => $user->id,
            ], [
                'image' =>  'profile/profile.jpeg',
            ]);
        }
    }
}

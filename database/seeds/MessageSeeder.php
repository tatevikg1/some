<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 100000; $i++)
        {
            do {
                $from = rand(1, 1000);
                $to = rand(1, 1000);
            } while ($from === $to);

            Message::create([
                'sender' => $from,
                'receiver' => $to,
                'text' => fake()->sentence,
                'created_at' => now(),
            ]);
        }
    }
}

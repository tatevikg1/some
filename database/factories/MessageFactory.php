<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $from = rand(1, 1500);
            $to = rand(1, 1500);
        } while ($from === $to);

        return [
            'sender' => $from,
            'receiver' => $to,
            'text' => fake()->sentence,
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Friendship;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 1000; $i++)
        {
            $data = $this->getInsertData();
            Friendship::updateOrInsert(
                [
                    'first_user' => $data['first_user'],
                    'second_user' => $data['second_user']
                ],
                $data
            );
        }
    }

    private function getInsertData(): array
    {
        do {
            $firstUser = rand(1, 1500);
            $secondUser = rand(1, 1500);
        } while ($firstUser === $secondUser);

        $actedUser = ($firstUser%2 === 1) ? $firstUser : $secondUser;

        switch ($actedUser) {
            case $firstUser:
                $status = 'pending';
                break;
            case $secondUser:
                $status = 'confirmed';
                break;
        }
        return [
            'first_user' => $firstUser,
            'second_user' => $secondUser,
            'acted_user' => $actedUser,
            'status' => $status,
        ];
    }
}

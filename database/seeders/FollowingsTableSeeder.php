<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Following;
use Faker\Factory as Faker;

class FollowingsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        // Generate followings table
        $users = User::pluck('id')->all();
        $userCount = count($users);
        $followEach = [
            11 => [19, 4, 8, 3, 20, 18, 17, 6, 9],
            8 => [13, 6, 9, 19, 16, 15, 20, 1, 18, 10, 2, 7, 4, 17, 11, 5, 3],
            4 => [3, 9, 8, 7, 16,  2, 11, 1, 15, 20],
            2 => [8, 20, 13, 16, 7, 19, 3, 15, 11, 18, 1, 14, 6, 4, 17],
            1 => [15, 16, 7, 8, 5, 14, 13, 6, 2, 4, 9, 20, 3, 19],
            20 => [1, 12, 14, 8, 17, 6, 19, 11, 3, 9, 18, 13, 16, 5],
            6 => [11, 3, 9, 18, 6, 19, 13, 16, 5, 7, 4, 1, 17],
            17 => [3, 11, 1, 19, 13, 6, 8, 17, 2],
            9 => [1, 7, 4, 10, 19, 13, 5, 20, 16],
            18 => [15,  4, 20, 3, 10, 19, 14, 5, 8],
            3 => [20, 8, 13, 16, 9, 18, 7, 1, 14, 6, 4, 17]
        ];
        foreach ($followEach as $userId => $followingIds) {
            foreach ($followingIds as $followingId) {
                Following::create([
                    'user_id' => $userId,
                    'following_user_id' => $followingId,
                ]);
            }
        }
    }
}

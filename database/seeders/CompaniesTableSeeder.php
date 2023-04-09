<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Faker\Factory as Faker;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Generate companies table
        $users = User::pluck('id')->all();
        foreach ($users as $userId) {
            Company::create([
                'user_id' => $userId,
                'name' => $faker->company,
                'catch_phrase' => $faker->catchPhrase,
                'bs' => $faker->bs,
            ]);
        }
    }
}

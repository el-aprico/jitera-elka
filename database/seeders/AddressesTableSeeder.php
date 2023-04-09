<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use Faker\Factory as Faker;

class AddressesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Generate addresses table
        $users = User::pluck('id')->all();
        foreach ($users as $userId) {
            Address::create([
                'user_id' => $userId,
                'street' => $faker->streetName,
                'suite' => $faker->secondaryAddress,
                'city' => $faker->city,
                'zipcode' => $faker->postcode,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
            ]);
        }
    }
}

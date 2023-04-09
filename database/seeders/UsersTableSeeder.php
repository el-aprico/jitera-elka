<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        $names = [
            'Prof. George Kuhlman III',
            'Mr. Daron Marks',
            'Aubrejohny Casper',
            'Issac Scjohnuster',
            'Miss Courtney Waters Sr.',
            'Miguel Wilkinjohn',
            'Frederik Bartoletti MD',
            'John Viva Schoen',
            'Ms. Brisa Little',
            'Lenore Hickle',
            'Toney Lemke',
            'Camron Stiedemann',
            'Luisa Frami',
            'Mr. Tom Russel',
            'Nikolas Jasjohnkolski',
            'Dr. Juanita Kuhlman',
            'Dr. Conner Herzog',
            'Bart John Volkman',
            'Easton Fay',
            'Belle Thiel V'
        ];
        $nameCount = count($names);
        // Generate users table
        for ($i = 0; $i < $nameCount; $i++) {
            User::create([
                'name' => $names[$i],
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'website' => $faker->url,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}

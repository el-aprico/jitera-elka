<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Address;
use App\Models\Company;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
    
        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->phoneNumber,
            'website' => $faker->url,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
    public function withAddressAndCompany()
    {
        return $this->hasAddress()->hasCompany();
    }
    
    public function hasAddress()
    {
        return $this->afterCreating(function (User $user) {
            Address::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
    
    public function hasCompany()
    {
        return $this->afterCreating(function (User $user) {
            Company::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}

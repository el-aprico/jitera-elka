<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Address;
use App\Models\User;
use Faker\Factory as Faker;

class AddressFactory extends Factory
{

    protected $model = Address::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    
        return [
            'user_id' => function() {
                return User::factory()->create()->id;
            },
            'street' => $this->faker->streetAddress,
            'suite' => $this->faker->secondaryAddress,
            'city' => $this->faker->city,
            'zipcode' => $this->faker->postcode,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude
        ];
    }
}

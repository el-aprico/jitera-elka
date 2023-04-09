<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company;
use App\Models\User;
use Faker\Factory as Faker;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Generate companies table
        return [
            'user_id' => function() {
                return User::factory()->create()->id;
            },
            'name' => $this->faker->company,
            'catch_phrase' => $this->faker->catchPhrase,
            'bs' => $this->faker->bs
        ];
    }
}

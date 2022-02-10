<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = Group::class;

    public function definition()
    {
        return [
            'name' => $this->faker->lastName(),
            'address1' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'country_id' => 'FR',
            'comment' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['ACTIVE', 'INACTIVE']),
            'created_by' => 'factory',
        ];
    }
}

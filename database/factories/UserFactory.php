<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_id' => $this->faker->randomElement(Role::all()->pluck('id')),
            'status' => $this->faker->randomElement(['ACTIVE', 'INACTIVE']),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('azerty'),
            'birth_date' => $this->faker->date('d/m/Y'),
            'gender_id' => $this->faker->randomElement(['1', '2']),
            'comment' => $this->faker->paragraphs(3, true),
            'created_by' => 'factory',
            'email_verified_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => 'Role ' . $this->faker->word,
            'comment' => 'Comment ' . $this->faker->sentence(),
        ];
    }
}

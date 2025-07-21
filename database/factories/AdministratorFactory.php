<?php
// database/factories/AdministratorFactory.php

namespace Database\Factories;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdministratorFactory extends Factory
{
    protected $model = Administrator::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // Puedes cambiar esto
            'status' => $this->faker->boolean(),
            'phone_number' => $this->faker->optional()->phoneNumber(),
            'user_id' => User::inRandomOrder()->first()?->id,
        ];
    }
}

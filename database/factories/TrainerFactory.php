<?php

namespace Database\Factories;

use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainerFactory extends Factory
{
    protected $model = Trainer::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->name,
            'specialty'  => $this->faker->randomElement(['Adiestramiento básico', 'Agilidad', 'Terapia canina']),
            'experience' => $this->faker->numberBetween(1, 20),
            'rating'     => $this->faker->randomFloat(2, 1, 5),
            'phone'      => $this->faker->numerify('3#########'),
            'email'      => $this->faker->unique()->safeEmail,
            'biography'  => $this->faker->paragraph,
            'user_id'    => User::factory(), // crea un usuario asociado
        ];
    }
}

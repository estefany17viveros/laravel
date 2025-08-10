<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\Veterinarian;
use App\Models\Shelter;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        // Lista de modelos que pueden tener roles
        $roleableModels = [
            Veterinarian::class,
            Shelter::class,
            Trainer::class,
            User::class,
        ];

        // Escoge un modelo al azar
        $roleableType = $this->faker->randomElement($roleableModels);

        return [
            'name_role'   => $this->faker->jobTitle(),
            'description' => $this->faker->sentence(),
            'roleable_id' => $roleableType::factory(), // Genera el ID con factory del modelo relacionado
            'roleable_type' => $roleableType, // Guarda el tipo
        ];
    }
}


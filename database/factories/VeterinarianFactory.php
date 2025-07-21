<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Veterinarian;
use Illuminate\Database\Eloquent\Factories\Factory;

class VeterinarianFactory extends Factory
{
    protected $model = Veterinarian::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'shedules' => $this->faker->randomElement(['8am - 5pm', '9am - 6pm']),
            'user_id' => User::factory(), // crea un usuario relacionado automáticamente
        ];
    }
}

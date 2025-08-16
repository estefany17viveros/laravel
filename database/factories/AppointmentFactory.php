<?php

// database/factories/AppointmentFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;
use App\Models\Veterinary;
use App\Models\Trainer;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'date' => $this->faker->date(),
            'description' => $this->faker->optional()->sentence(),
            'veterinarian_id' => Veterinary::inRandomOrder()->first()?->id,
            'trainer_id' => Trainer::inRandomOrder()->first()?->id,
        ];
    }
}

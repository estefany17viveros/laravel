<?php

// database/factories/AppointmentFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Veterinarian;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\Trainer;
use App\Models\Pet;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'date' => $this->faker->date(),
            'description' => $this->faker->optional()->sentence(),
            'user_id' => User::inRandomOrder()->first()?->id,
            'veterinarian_id' => Veterinarian::inRandomOrder()->first()?->id,
            'service_id' => Service::inRandomOrder()->first()?->id,
            'schedule_id' => Schedule::inRandomOrder()->first()?->id,
            'trainer_id' => Trainer::inRandomOrder()->first()?->id,
            'pet_id' => Pet::inRandomOrder()->first()?->id,
        ];
    }
}

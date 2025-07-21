<?php
// database/factories/RequesttFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Requestt;
use App\Models\User;
use App\Models\Shelter;
use App\Models\Service;
use App\Models\Appointment;

class RequesttFactory extends Factory
{
    protected $model = Requestt::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->optional()->dateTime(),
            'priority' => $this->faker->numberBetween(1, 5),
            'solicitation_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),

            'user_id' => User::inRandomOrder()->first()?->id,
            'shelter_id' => Shelter::inRandomOrder()->first()?->id,
            'services_id' => Service::inRandomOrder()->first()?->id,
            'appointment_id' => Appointment::inRandomOrder()->first()?->id,
        ];
    }
}

<?php

// database/factories/ScheduleFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Schedule;
use App\Models\Service;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'hour' => $this->faker->numberBetween(0, 23),
            'location' => $this->faker->address(),
            'service_id' => Service::inRandomOrder()->first()?->id,
        ];
    }
}

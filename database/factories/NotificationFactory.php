<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'Title'       => $this->faker->sentence(4),
            'Description' => $this->faker->paragraph,
            'user_id'     => User::factory(), // crea un usuario relacionado
        ];
    }
}

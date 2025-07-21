<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use App\Models\Forum;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    protected $model = Topic::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'creation_date' => $this->faker->date(),
            'user_id' => User::factory(), // se crea un usuario si no existe
            'forum_id' => Forum::factory(), // se crea un foro si no existe
        ];
    }
}

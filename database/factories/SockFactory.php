<?php

namespace Database\Factories;

use App\Models\Sock;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class SockFactory extends Factory
{
    protected $model = Sock::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'URL' => $this->faker->imageUrl(), // o ->url() si no son imágenes
            'Upload_Date' => $this->faker->date(),
            'topic_id' => Topic::factory(),
        ];
    }
}

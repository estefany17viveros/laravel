<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Veterinary;

class VeterinarySeeder extends Seeder
{
    public function run(): void
    {
        Veterinary::factory()->count(10)->create();
    }
}

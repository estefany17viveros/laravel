<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Veterinarian;

class VeterinarianSeeder extends Seeder
{
    public function run(): void
    {
        Veterinarian::factory()->count(10)->create();
    }
}

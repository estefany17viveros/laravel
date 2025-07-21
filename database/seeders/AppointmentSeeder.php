<?php
// database/seeders/AppointmentSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        Appointment::factory()->count(10)->create();
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sock;

class SockSeeder extends Seeder
{
    public function run(): void
    {
        Sock::factory()->count(10)->create();
    }
}

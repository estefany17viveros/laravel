<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\paymentmethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        paymentmethod::factory()->count(10)->create();
    }
}

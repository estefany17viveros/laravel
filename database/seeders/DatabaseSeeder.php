<?php

namespace Database\Seeders;

use App\Models\administrator;
use App\Models\appointment;
use App\Models\category;
use App\Models\paymentmethod;
use App\Models\requestt;
use App\Models\schedule;
use App\Models\service;
use App\Models\shelter;
use App\Models\shipment;
use App\Models\shopping_cart;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        VeterinarianSeeder::class,
         PaymentMethodSeeder::class,
          ForumSeeder::class,
          TrainerSeeder::class,
           NotificationSeeder::class,
           TopicSeeder::class,
           AnswerSeeder::class,
         SockSeeder::class,
         shelterSeeder::class,
         categorySeeder::class,
         OrderItemSeeder::class,
         OrderSeeder::class,
         shipmentSeeder::class,
         shoppingcartSeeder::class,
         serviceSeeder::class,
         scheduleSeeder::class,
           PetSeeder::class,
           appointmentSeeder::class,
           RequesttSeeder::class,
           AdoptionSeeder::class,
           paymentSeeder::class,
           administratorSeeder::class,
              UserSeeder::class,
    ]);
    }
}

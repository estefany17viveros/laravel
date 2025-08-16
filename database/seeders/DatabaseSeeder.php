<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Requestt;
use App\Models\Service;
use App\Models\Shelter;
use App\Models\Shipment;
use App\Models\ShoppingCart;
use App\Models\User;
use App\Models\Veterinary;
use App\Models\Forum;
use App\Models\Trainer;
use App\Models\Notification;
use App\Models\Topic;
use App\Models\Answer;
use App\Models\Socks;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Pet;
use App\Models\Adoption;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Role;


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
          VeterinarySeeder::class,
          PaymentMethodSeeder::class,
          ForumSeeder::class,
          TrainerSeeder::class,
          NotificationSeeder::class,
          TopicSeeder::class,
          AnswerSeeder::class,
          SockSeeder::class,
          ShelterSeeder::class,
          CategorySeeder::class,
          OrderItemSeeder::class,
          OrderSeeder::class,
          ShipmentSeeder::class,
          ShoppingCartSeeder::class,
          ServiceSeeder::class,
          PetSeeder::class,
          AppointmentSeeder::class,
          AdoptionSeeder::class,
          RequesttSeeder::class,
          paymentSeeder::class,
          UserSeeder::class,
          PaymentTypeSeeder::class,
          InventorySeeder::class,
          ProductSeeder::class,
          RoleSeeder::class,







    ]);
    }
}

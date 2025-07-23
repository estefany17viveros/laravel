<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\Appointment;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Pet;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('veterinarians', VeterinarianController::class);

Route::apiResource('payment-methods', PaymentMethodController::class);

Route::apiResource('forums', ForumController::class);
Route::apiResource('notifications', NotificationController::class);
Route::apiResource('trainers', TrainerController::class);
Route::apiResource('topics', TopicController::class);
Route::apiResource('answers', AnswerController::class);
Route::apiResource('socks', SockController::class);
Route::apiResource('shelters', ShelterController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('inventories', InventoryController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('orderitems',  OrderItemController::class);
Route::apiResource('shipments', ShipmentController::class);
Route::apiResource('shopping-carts', ShoppingCartController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('pets', PetController::class);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('requestts', RequesttController::class);
Route::apiResource('adoptions', AdoptionController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('administrators', AdministratorController::class);


route::get('/products/{product}/category', [ProductController::class, 'category']);
    $relations = collect($relations)->intersect($allowIncluded);
        if ($relations->isNotEmpty()) {
            $query->with($relations->toArray());                        
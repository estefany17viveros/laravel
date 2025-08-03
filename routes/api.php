<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
estas son rutas puntuales que se pueden usar para autenticar usuarios, obtener información del usuario autenticado, cerrar sesión y refrescar el token de acceso.
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
*/
Route::apiResource('administrators', AdministratorController::class);
Route::apiResource('forums', ForumController::class);
Route::apiResource('topics', TopicController::class);
Route::apiResource('answers', AnswerController::class);
// Route::apiResource('averages', AverageController::class);
Route::apiResource('trainers', TrainerController::class);
Route::apiResource('veterinaries', VeterinarianController::class);
Route::apiResource('shelters', ShelterController::class);
Route::apiResource('pets', PetController::class);
Route::apiResource('adoptions', AdoptionController::class);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('notifications', NotificationController::class);
Route::apiResource('requestts', RequesttController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('shoppingcars', ShoppingCartController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('shipments', ShipmentController::class);
Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('inventories', InventoryController::class);
Route::apiResource('orderitems', OrderItemController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('paymentmethos', PaymentMethodController::class);


// Route::get('/order-items', [OrderItemController::class, 'index']);

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->decimal('amount', 10, 2)->unsigned(); 
        $table->timestamp('date');
        $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('payment_types_id')->constrained('payment_types')->onDelete('cascade');
        $table->unsignedBigInteger('payable_id'); // Para relación polimórfica
        $table->string('payable_type'); 

        $table->timestamps();
    });
}


public function down(): void
{
    Schema::dropIfExists('payments');
}
};
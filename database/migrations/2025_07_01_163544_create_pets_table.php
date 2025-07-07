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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nombre
            $table->integer('age'); // edad
            $table->string('species'); // especie
            $table->string('breed'); // raza
            $table->decimal('size', 8, 2); // tamano
            $table->string('sex'); // sexo
            $table->longText('description')->nullable(); // descripcion

            $table->string('gmail');
            $table->longText('biography')->nullable();

            $table->foreignId('trainer_id')->constrained('trainers')->OnDelete('cascade');
            $table->foreignId('appointment_id')->constrained('appointments')->OnDelete('cascade');
            $table->foreignId('shelter_id')->constrained('shelters')->OnDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->OnDelete('cascadde');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};

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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name_role');
            $table->string('description');
            $table->foreignId('veterinarian_id')->constrained('veterinarians')->OnDelete('cascade');
            $table->foreignId('shelter_id')->constrained('shelters')->OnDelete('cascade');
            $table->foreignId('trainer_id')->constrained('trainers')->OnDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

                    

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

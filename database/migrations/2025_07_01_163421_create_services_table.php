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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
             $table->string('name');
    $table->decimal('price', 8, 2);
    $table->date('duration');
    $table->text('description')->nullable();
    $table->unsignedBigInteger('veterinarian_id')->nullable()->constrained('veterinarians')->ondelete('set null');
   
    $table->unsignedBigInteger('trainer_id')->nullable()->constrained('trainers')->ondelete('set null');
   
    $table->unsignedBigInteger('request_id')->nullable()->constrained('requests')->ondelete('set null');
   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

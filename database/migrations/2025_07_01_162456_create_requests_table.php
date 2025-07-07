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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('shelter_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('services_id')->nullable()->constrained('services')->nullOnDelete();
    $table->dateTime('date')->nullable();
    $table->integer('priority');
    $table->string('solicitation_status');
    $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};

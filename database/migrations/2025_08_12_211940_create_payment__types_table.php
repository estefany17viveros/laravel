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
        Schema::create('payment__types', function (Blueprint $table) {
            $table->id();
             $table->string('payable_type'); 
            $table->unsignedBigInteger('payable_id');
            $table->decimal('amount', 10, 2); 
            $table->string('method'); 
            $table->string('status')->default('pending'); 
            $table->timestamps(); 

            $table->index(['payable_type', 'payable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment__types');
    }
};

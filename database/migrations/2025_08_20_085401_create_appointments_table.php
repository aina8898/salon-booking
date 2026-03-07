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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->restrictOndelete();
            $table->foreignId('staff_id')->constrained()->restrictOnDelete();
            $table->dateTime('appointment_start');
            $table->decimal('total_price',10,2)->default(0);
            $table->unsignedInteger('total_minutes')->default(0);
            $table->json('services_json');
            $table->text('note')->nullable();
            $table->timestamps();
   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

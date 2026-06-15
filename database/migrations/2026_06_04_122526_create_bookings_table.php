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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pencari Kos
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade'); // Kamar yg dipesan
            $table->date('check_in_date');
            $table->integer('duration_months')->default(1);
            $table->integer('total_price');
            $table->enum('status', ['Pending', 'Verified', 'Active', 'Completed', 'Cancelled'])->default('Pending');
            $table->enum('payment_status', ['Unpaid', 'Checking', 'Paid'])->default('Unpaid');
            $table->string('payment_proof')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
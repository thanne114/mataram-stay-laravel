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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['Putra', 'Putri', 'Campur']);
            $table->enum('area', ['Mataram', 'Ampenan', 'Cakranegara', 'Selaparang', 'Sekarbela', 'Sandubaya']);
            $table->text('address');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('description')->nullable();
            $table->string('main_image')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
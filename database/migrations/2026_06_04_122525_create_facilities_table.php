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
        // Tabel Master Fasilitas
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // Tabel Pivot/Penghubung (Kos A punya fasilitas apa saja)
        Schema::create('facility_property', function (Blueprint $table) {
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('facilities')->onDelete('cascade');
            $table->primary(['property_id', 'facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_property');
        Schema::dropIfExists('facilities');
    }
};
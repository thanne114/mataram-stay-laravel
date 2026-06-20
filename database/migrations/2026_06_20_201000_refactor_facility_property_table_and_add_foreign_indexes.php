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
        $driver = Schema::getConnection()->getDriverName();

        // 1. Refactor facility_property pivot table (skip on SQLite where table is created with composite key initially)
        if ($driver !== 'sqlite') {
            Schema::table('facility_property', function (Blueprint $table) {
                // Drop auto-incrementing id column if present
                if (Schema::hasColumn('facility_property', 'id')) {
                    $table->dropColumn('id');
                }
                
                // Set composite primary key
                $table->primary(['property_id', 'facility_id']);
            });
        }

        // 2. Add indexes on foreign keys for improved query performance
        Schema::table('properties', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->index('property_id');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('room_type_id');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index('property_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['property_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['room_type_id']);
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->dropIndex(['property_id']);
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        $driver = Schema::getConnection()->getDriverName();
        if ($driver !== 'sqlite') {
            Schema::table('facility_property', function (Blueprint $table) {
                $table->dropPrimary(['property_id', 'facility_id']);
                $table->id();
            });
        }
    }
};

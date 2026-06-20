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
        Schema::table('properties', function (Blueprint $table) {
            $table->index('area');
            $table->index('type');
            $table->index('status');
            $table->index('deleted_at');
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->index('price_per_month');
            $table->index('available_rooms');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('status');
            $table->index('payment_status');
            $table->index('is_approved');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['area']);
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
            $table->dropIndex(['deleted_at']);
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->dropIndex(['price_per_month']);
            $table->dropIndex(['available_rooms']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['is_approved']);
            $table->dropIndex(['user_id']);
        });
    }
};

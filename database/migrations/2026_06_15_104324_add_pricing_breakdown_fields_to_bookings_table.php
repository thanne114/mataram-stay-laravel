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
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('room_subtotal')->default(0)->after('total_price');
            $table->integer('admin_fee')->default(0)->after('room_subtotal');
            $table->integer('commission_fee')->default(0)->after('admin_fee');
            $table->integer('net_owner_amount')->default(0)->after('commission_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['room_subtotal', 'admin_fee', 'commission_fee', 'net_owner_amount']);
        });
    }
};

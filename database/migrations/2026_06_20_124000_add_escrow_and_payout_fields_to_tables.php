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
        Schema::table('users', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('selfie_photo');
            $table->string('bank_account_name')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_account_name');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('escrow_status')->default('held')->after('is_approved'); // held, released, refunded
            $table->string('payout_status')->nullable()->after('escrow_status'); // pending, success, failed
            $table->string('payout_reference')->nullable()->after('payout_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_account_name', 'bank_account_number']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['escrow_status', 'payout_status', 'payout_reference']);
        });
    }
};

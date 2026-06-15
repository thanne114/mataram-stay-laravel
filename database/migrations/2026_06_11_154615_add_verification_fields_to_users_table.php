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
            $table->string('identity_type')->nullable();
            $table->string('identity_photo')->nullable();
            $table->string('selfie_photo')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('phone_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['identity_type', 'identity_photo', 'selfie_photo', 'is_verified', 'phone_verified_at']);
        });
    }
};

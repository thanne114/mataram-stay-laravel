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
            if (Schema::hasColumn('users', 'social_id')) {
                $table->dropColumn('social_id');
            }
            if (Schema::hasColumn('users', 'auth_provider')) {
                $table->dropColumn('auth_provider');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('social_id')->nullable()->after('id');
            $table->string('auth_provider')->nullable()->after('social_id');
        });
    }
};

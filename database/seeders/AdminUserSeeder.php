<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Seeker
        User::firstOrCreate(
            ['email' => 'seeker@mataramstay.com'],
            [
                'name' => 'Budi Seeker',
                'username' => 'budi_seeker',
                'password' => Hash::make('password'),
                'role' => 'seeker',
                'no_whatsapp' => '081234567890',
            ]
        );

        // Seed Owner
        User::firstOrCreate(
            ['email' => 'owner@mataramstay.com'],
            [
                'name' => 'Andi Owner',
                'username' => 'andi_owner',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'no_whatsapp' => '081234567891',
            ]
        );

        // Seed Admin Produksi Google SSO
        User::updateOrCreate(
            ['email' => 'mataramstay@gmail.com'],
            [
                'name' => 'Admin Mataram Stay',
                'username' => 'mataram_admin',
                'role' => 'admin',
                'no_whatsapp' => '081234567895',
                'email_verified_at' => now(),
            ]
        );
    }
}

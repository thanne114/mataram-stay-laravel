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

        // Seed Admin
        User::firstOrCreate(
            ['email' => 'admin@mataramstay.com'],
            [
                'name' => 'Admin Mataram Stay',
                'username' => 'admin_stay',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'no_whatsapp' => '081234567892',
            ]
        );
    }
}

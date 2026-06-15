<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class SimulationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a seeker needing verification
        $seeker = User::updateOrCreate(
            ['email' => 'simulasi_seeker@mataramstay.com'],
            [
                'name' => 'Rian Pencari',
                'username' => 'rian_pencari',
                'password' => Hash::make('password'),
                'role' => 'seeker',
                'no_whatsapp' => '089876543210',
                'is_verified' => false,
                'identity_type' => 'KTP',
                'identity_photo' => 'identities/rian_ktp.png',
                'selfie_photo' => 'identities/rian_selfie.png',
            ]
        );

        // Create dummy small images
        $dummyImage = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='); // Tiny 1x1 PNG

        Storage::disk('local')->put('identities/rian_ktp.png', Crypt::encryptString($dummyImage));
        Storage::disk('local')->put('identities/rian_selfie.png', Crypt::encryptString($dummyImage));

        // 2. Create an owner or get existing
        $owner = User::where('role', 'owner')->first();
        if (!$owner) {
            $owner = User::create([
                'name' => 'Andi Owner',
                'email' => 'owner@mataramstay.com',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'no_whatsapp' => '081234567891',
            ]);
        }

        // 3. Create a property in draft status needing approval
        Property::updateOrCreate(
            ['name' => 'Kos Sahara Simulator'],
            [
                'user_id' => $owner->id,
                'slug' => 'kos-sahara-simulator',
                'type' => 'Campur',
                'area' => 'Mataram',
                'address' => 'Jl. Sahara No. 5, Mataram',
                'latitude' => '-8.6000',
                'longitude' => '116.1000',
                'description' => 'Kos simulator dengan kenyamanan maksimal dan fasilitas lengkap bergaya padang pasir modern.',
                'main_image' => 'properties/kos_exterior_1.png',
                'is_verified' => false,
                'status' => 'draft',
            ]
        );
    }
}

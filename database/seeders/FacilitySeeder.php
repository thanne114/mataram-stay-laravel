<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'WiFi',           'icon' => 'wifi'],
            ['name' => 'AC',             'icon' => 'ac_unit'],
            ['name' => 'KM Dalam',       'icon' => 'shower'],
            ['name' => 'Kasur',          'icon' => 'bed'],
            ['name' => 'Lemari',         'icon' => 'shelves'],
            ['name' => 'Parkir',         'icon' => 'local_parking'],
            ['name' => 'Dapur',          'icon' => 'cooking'],
            ['name' => 'CCTV',           'icon' => 'videocam'],
            ['name' => 'TV Kabel',       'icon' => 'tv'],
            ['name' => 'Meja Belajar',   'icon' => 'desk'],
            ['name' => 'Jemuran',        'icon' => 'dry_cleaning'],
            ['name' => 'Laundry',        'icon' => 'local_laundry_service'],
            ['name' => 'Keamanan 24 Jam', 'icon' => 'security'],
            ['name' => 'Air Panas',      'icon' => 'hot_tub'],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}

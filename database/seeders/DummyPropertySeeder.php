<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\Facility;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyPropertySeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('role', 'owner')->first();
        $seeker = User::where('role', 'seeker')->first();

        if (!$owner || !$seeker) {
            $this->command->error('Harap jalankan AdminUserSeeder terlebih dahulu!');
            return;
        }

        // Get facilities
        $wifi = Facility::where('name', 'WiFi')->first();
        $ac = Facility::where('name', 'AC')->first();
        $kmDalam = Facility::where('name', 'KM Dalam')->first();
        $kasur = Facility::where('name', 'Kasur')->first();
        $lemari = Facility::where('name', 'Lemari')->first();
        $parkir = Facility::where('name', 'Parkir')->first();
        $dapur = Facility::where('name', 'Dapur')->first();
        $cctv = Facility::where('name', 'CCTV')->first();
        $meja = Facility::where('name', 'Meja Belajar')->first();
        $laundry = Facility::where('name', 'Laundry')->first();

        // 1. Kos Selaparang Modern
        $kos1 = Property::firstOrCreate(
            ['name' => 'Kos Selaparang Modern'],
            [
                'user_id' => $owner->id,
                'slug' => 'kos-selaparang-modern',
                'type' => 'Campur',
                'area' => 'Selaparang',
                'address' => 'Jl. Jenderal Sudirman No. 12, Selaparang, Mataram',
                'latitude' => '-8.5786',
                'longitude' => '116.1123',
                'description' => 'Kos-kosan modern dan aesthetic di pusat kota Mataram. Lingkungan tenang, aman, dan sangat dekat dengan pusat perkantoran dan perbelanjaan. Sangat cocok untuk mahasiswa maupun karyawan/karyawati.',
                'main_image' => 'properties/kos_exterior_1.png',
                'is_verified' => true,
                'status' => 'published',
            ]
        );

        // Attach facilities for Kos 1
        $kos1->facilities()->sync([
            $wifi->id, $ac->id, $kmDalam->id, $kasur->id, $lemari->id, $parkir->id, $cctv->id
        ]);

        // Room Types for Kos 1
        $room1a = RoomType::firstOrCreate(
            ['property_id' => $kos1->id, 'name' => 'Kamar Deluxe AC'],
            [
                'price_per_month' => 1500000,
                'total_rooms' => 10,
                'available_rooms' => 8,
                'description' => 'Kamar luas 3x4 meter dilengkapi dengan AC, tempat tidur springbed, lemari pakaian, meja belajar, dan kamar mandi dalam lengkap dengan shower.',
            ]
        );

        $room1b = RoomType::firstOrCreate(
            ['property_id' => $kos1->id, 'name' => 'Kamar Standard Non-AC'],
            [
                'price_per_month' => 1000000,
                'total_rooms' => 5,
                'available_rooms' => 3,
                'description' => 'Kamar standard dengan kipas angin, kasur busa nyaman, lemari kecil, dan kamar mandi dalam.',
            ]
        );

        // 2. Kos Putri Sakinah Cakranegara
        $kos2 = Property::firstOrCreate(
            ['name' => 'Kos Putri Sakinah Cakranegara'],
            [
                'user_id' => $owner->id,
                'slug' => 'kos-putri-sakinah-cakranegara',
                'type' => 'Putri',
                'area' => 'Cakranegara',
                'address' => 'Jl. Pejanggik Gang VII No. 4, Cakranegara, Mataram',
                'latitude' => '-8.5901',
                'longitude' => '116.1345',
                'description' => 'Kos khusus putri dengan sistem keamanan terpadu. Dekat dengan kampus, minimarket, dan area kuliner Cakranegara. Suasana bersih, nyaman, dan kekeluargaan.',
                'main_image' => 'properties/kos_interior_1.png',
                'is_verified' => true,
                'status' => 'published',
            ]
        );

        $kos2->facilities()->sync([
            $wifi->id, $kmDalam->id, $kasur->id, $lemari->id, $parkir->id, $meja->id, $dapur->id
        ]);

        $room2a = RoomType::firstOrCreate(
            ['property_id' => $kos2->id, 'name' => 'Kamar Single KM Dalam'],
            [
                'price_per_month' => 850000,
                'total_rooms' => 12,
                'available_rooms' => 5,
                'description' => 'Kamar minimalis 3x3 meter untuk satu orang putri, sudah termasuk kasur, lemari, meja belajar, dan kamar mandi dalam.',
            ]
        );

        // 3. Kos Putra Elite Ampenan
        $kos3 = Property::firstOrCreate(
            ['name' => 'Kos Putra Elite Ampenan'],
            [
                'user_id' => $owner->id,
                'slug' => 'kos-putra-elite-ampenan',
                'type' => 'Putra',
                'area' => 'Ampenan',
                'address' => 'Jl. Saleh Sungkar No. 45, Ampenan, Mataram',
                'latitude' => '-8.5721',
                'longitude' => '116.0823',
                'description' => 'Hunian nyaman khusus putra di kawasan bersejarah Ampenan. Dekat dengan pantai, kafe, dan akses transportasi umum mudah. Fasilitas lengkap dengan parkiran luas.',
                'main_image' => 'properties/kos_interior_2.png',
                'is_verified' => false,
                'status' => 'published',
            ]
        );

        $kos3->facilities()->sync([
            $wifi->id, $ac->id, $kmDalam->id, $kasur->id, $lemari->id, $parkir->id, $laundry->id
        ]);

        $room3a = RoomType::firstOrCreate(
            ['property_id' => $kos3->id, 'name' => 'Kamar Elite King'],
            [
                'price_per_month' => 1200000,
                'total_rooms' => 8,
                'available_rooms' => 4,
                'description' => 'Kamar eksekutif dengan tempat tidur queen size, pendingin ruangan AC, lemari pakaian 2 pintu, kamar mandi dalam dengan shower.',
            ]
        );

        // Seed realistic bookings
        // 1. Completed booking for Kos 1 Deluxe AC -> allows review
        $booking1 = Booking::firstOrCreate(
            [
                'user_id' => $seeker->id,
                'room_type_id' => $room1a->id,
                'check_in_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            ],
            [
                'duration_months' => 3,
                'total_price' => 1500000 * 3,
                'status' => 'Completed',
                'payment_status' => 'Paid',
                'payment_proof' => 'proofs/dummy_proof_1.jpg',
            ]
        );

        // Review for Kos 1 Deluxe AC
        Review::firstOrCreate(
            ['booking_id' => $booking1->id],
            [
                'property_id' => $kos1->id,
                'user_id' => $seeker->id,
                'rating' => 5,
                'comment' => 'Kamar sangat bersih dan luas. Pemilik kos ramah sekali, lokasinya sangat strategis di tengah kota Mataram. Sangat merekomendasikan kos ini!',
            ]
        );

        // 2. Pending booking for Kos 2 Single KM Dalam
        $booking2 = Booking::firstOrCreate(
            [
                'user_id' => $seeker->id,
                'room_type_id' => $room2a->id,
                'check_in_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            ],
            [
                'duration_months' => 1,
                'total_price' => 850000 * 1,
                'status' => 'Pending',
                'payment_status' => 'Checking',
                'payment_proof' => 'proofs/dummy_proof_2.jpg',
            ]
        );

        // 3. Active booking for Kos 3 Elite King
        $booking3 = Booking::firstOrCreate(
            [
                'user_id' => $seeker->id,
                'room_type_id' => $room3a->id,
                'check_in_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            ],
            [
                'duration_months' => 6,
                'total_price' => 1200000 * 6,
                'status' => 'Active',
                'payment_status' => 'Paid',
                'payment_proof' => 'proofs/dummy_proof_3.jpg',
            ]
        );
    }
}

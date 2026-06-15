<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCancellationTest extends TestCase
{
    use RefreshDatabase;

    protected $seeker;
    protected $owner;
    protected $property;
    protected $roomType;
    protected $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seeker = User::create([
            'name' => 'Budi Seeker',
            'email' => 'budi.seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $this->owner = User::create([
            'name' => 'Andi Owner',
            'email' => 'andi.owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Sahara Simulator',
            'slug' => 'kos-sahara-simulator',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jl. Sahara No. 5, Mataram',
            'latitude' => '-8.6000',
            'longitude' => '116.1000',
            'description' => 'Kos simulator',
            'main_image' => 'properties/kos_exterior_1.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        $this->roomType = RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Kamar Deluxe AC',
            'price_per_month' => 1500000,
            'total_rooms' => 10,
            'available_rooms' => 8,
            'description' => 'Kamar Deluxe',
        ]);

        $this->booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(5)->format('Y-m-d'),
            'duration_months' => 1,
            'room_subtotal' => 1500000,
            'admin_fee' => 2500,
            'commission_fee' => 75000,
            'net_owner_amount' => 1425000,
            'total_price' => 1502500,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
        ]);
    }

    public function test_seeker_can_cancel_their_own_pending_booking(): void
    {
        $response = $this->actingAs($this->seeker)->post(route('booking.cancel', $this->booking));
        $response->assertRedirect();

        $this->booking->refresh();
        $this->assertEquals('Cancelled', $this->booking->status);
        $this->assertEquals('Unpaid', $this->booking->payment_status);
    }

    public function test_seeker_cannot_cancel_others_booking(): void
    {
        $otherSeeker = User::create([
            'name' => 'Other Seeker',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $response = $this->actingAs($otherSeeker)->post(route('booking.cancel', $this->booking));
        $response->assertStatus(403);

        $this->booking->refresh();
        $this->assertEquals('Pending', $this->booking->status);
    }

    public function test_seeker_cannot_cancel_active_booking(): void
    {
        $this->booking->update(['status' => 'Active', 'payment_status' => 'Paid']);

        $response = $this->actingAs($this->seeker)->post(route('booking.cancel', $this->booking));
        $response->assertSessionHas('error', 'Booking ini tidak dapat dibatalkan.');

        $this->booking->refresh();
        $this->assertEquals('Active', $this->booking->status);
    }

    public function test_cancelled_booking_detail_page_displays_rebook_button(): void
    {
        $this->booking->update(['status' => 'Cancelled']);

        $response = $this->actingAs($this->seeker)->get(route('booking.show', $this->booking));
        $response->assertStatus(200);
        $response->assertSee('Pemesanan Dibatalkan / Kedaluwarsa');
        $response->assertSee('Pesan Ulang Kamar');
        $response->assertSee(route('booking.create', ['room_type_id' => $this->booking->room_type_id]));
    }
}

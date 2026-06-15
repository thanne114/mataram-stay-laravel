<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Booking;
use App\Models\Property;
use App\Models\RoomType;
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
            'name' => 'Seeker Test',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $this->owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Sahara',
            'slug' => 'kos-sahara',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jl. Sahara No. 5, Mataram',
            'latitude' => '-8.6000',
            'longitude' => '116.1000',
            'description' => 'Kos Sahara',
            'main_image' => 'properties/test.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        $this->roomType = RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Kamar Sahara Deluxe',
            'price_per_month' => 1500000,
            'total_rooms' => 5,
            'available_rooms' => 5,
            'description' => 'Kamar Deluxe',
        ]);

        $this->booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(5),
            'duration_months' => 1,
            'room_subtotal' => 1500000,
            'admin_fee' => 2500,
            'commission_fee' => 75000,
            'net_owner_amount' => 1425000,
            'total_price' => 1502500,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'payment_token' => 'dummy-token',
        ]);
    }

    public function test_seeker_can_cancel_their_own_pending_unpaid_booking(): void
    {
        $response = $this->actingAs($this->seeker)->post(route('booking.cancel', $this->booking));
        
        $response->assertRedirect('/dashboard-seeker');
        $response->assertSessionHas('success', 'Pemesanan Anda berhasil dibatalkan.');
        
        $this->booking->refresh();
        $this->assertEquals('Cancelled', $this->booking->status);
    }

    public function test_seeker_cannot_cancel_other_users_booking(): void
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

    public function test_seeker_cannot_cancel_paid_booking(): void
    {
        $this->booking->update([
            'status' => 'Active',
            'payment_status' => 'Paid',
        ]);

        $response = $this->actingAs($this->seeker)->post(route('booking.cancel', $this->booking));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pemesanan tidak dapat dibatalkan.');

        $this->booking->refresh();
        $this->assertEquals('Active', $this->booking->status);
    }

    public function test_booking_details_page_shows_cancel_button_for_pending_unpaid_booking(): void
    {
        $response = $this->actingAs($this->seeker)->get(route('booking.show', $this->booking));
        
        $response->assertStatus(200);
        $response->assertSee('Batalkan Pemesanan');
        $response->assertDontSee('Pesan Ulang Kamar');
    }

    public function test_booking_details_page_shows_rebook_button_for_cancelled_booking(): void
    {
        $this->booking->update([
            'status' => 'Cancelled',
        ]);

        $response = $this->actingAs($this->seeker)->get(route('booking.show', $this->booking));
        
        $response->assertStatus(200);
        $response->assertSee('Pesan Ulang Kamar');
        $response->assertDontSee('Batalkan Pemesanan');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\RoomType;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelStaleBookingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancel_stale_bookings_after_30_minutes(): void
    {
        // 1. Create a seeker and owner
        $owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner.test@example.com',
            'role' => 'owner',
            'password' => bcrypt('password'),
        ]);

        $seeker = User::create([
            'name' => 'Seeker Test',
            'email' => 'seeker.test@example.com',
            'role' => 'seeker',
            'password' => bcrypt('password'),
        ]);

        // 2. Create property and room type
        $property = Property::create([
            'user_id' => $owner->id,
            'name' => 'Kos Test',
            'slug' => 'kos-test',
            'description' => 'Test',
            'type' => 'campur',
            'area' => 'Ampenan',
            'address' => 'Test',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'status' => 'approved',
        ]);

        $roomType = RoomType::create([
            'property_id' => $property->id,
            'name' => 'Kamar Standard',
            'price_per_month' => 500000,
            'available_rooms' => 5,
            'total_rooms' => 5,
        ]);

        // 3. Create a stale booking (31 minutes ago)
        $staleBooking = Booking::create([
            'user_id' => $seeker->id,
            'room_type_id' => $roomType->id,
            'check_in_date' => now()->toDateString(),
            'duration_months' => 1,
            'room_subtotal' => 500000,
            'admin_fee' => 2500,
            'commission_fee' => 25000,
            'net_owner_amount' => 475000,
            'total_price' => 502500,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'is_approved' => false,
        ]);
        $staleBooking->created_at = now()->subMinutes(31);
        $staleBooking->timestamps = false;
        $staleBooking->save();

        // 4. Create a fresh booking (15 minutes ago)
        $freshBooking = Booking::create([
            'user_id' => $seeker->id,
            'room_type_id' => $roomType->id,
            'check_in_date' => now()->toDateString(),
            'duration_months' => 1,
            'room_subtotal' => 500000,
            'admin_fee' => 2500,
            'commission_fee' => 25000,
            'net_owner_amount' => 475000,
            'total_price' => 502500,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'is_approved' => false,
        ]);
        $freshBooking->created_at = now()->subMinutes(15);
        $freshBooking->timestamps = false;
        $freshBooking->save();

        // 5. Run the cancel-stale command
        $this->artisan('bookings:cancel-stale')
            ->expectsOutput("Scanning for stale Pending bookings created before: " . now()->subMinutes(30)->toDateTimeString())
            ->expectsOutput("Cancelled stale Booking ID {$staleBooking->id} (created: " . $staleBooking->fresh()->created_at->toDateTimeString() . ", User: {$seeker->name}).")
            ->expectsOutput("Finished. Total stale bookings cancelled: 1")
            ->assertExitCode(0);

        // 6. Assert statuses in database
        $this->assertEquals('Cancelled', $staleBooking->fresh()->status);
        $this->assertEquals('Pending', $freshBooking->fresh()->status);
    }
}

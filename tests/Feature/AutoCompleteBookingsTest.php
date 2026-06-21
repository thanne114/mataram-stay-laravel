<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AutoCompleteBookingsTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $seeker;
    protected $property;
    protected $roomType;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Owner with complete bank details for payout
        $this->owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Owner Test Name',
        ]);

        // Create Seeker
        $this->seeker = User::create([
            'name' => 'Seeker Test',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        // Create Property
        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Test',
            'slug' => 'kos-test',
            'type' => 'Putra',
            'area' => 'Selaparang',
            'address' => 'Jl. Test No. 12',
            'latitude' => '-8.5786',
            'longitude' => '116.1123',
            'description' => 'Kos test description',
            'main_image' => 'properties/test.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        // Create Room Type
        $this->roomType = RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Kamar Test Deluxe',
            'price_per_month' => 1000000,
            'total_rooms' => 5,
            'available_rooms' => 3,
            'description' => 'Room description',
        ]);
    }

    /**
     * Test expired active bookings are automatically completed, inventory is restored, and payout is sent.
     */
    public function test_auto_completes_expired_active_bookings(): void
    {
        // Fake Midtrans Iris payout endpoint
        Http::fake([
            '*/payouts' => Http::response([
                'payouts' => [
                    [
                        'reference_no' => 'iris-ref-test-auto-999'
                    ]
                ]
            ], 200)
        ]);

        // Set MIDTRANS_IRIS_API_KEY in config dynamically for this test execution
        config(['services.midtrans.iris_api_key' => 'test-fake-key']);

        // Target Date is today
        // Active booking check-in = 2 months ago, duration = 1 month, so checkout is 1 month ago (expired)
        $checkInDate = Carbon::now()->subMonths(2)->toDateString();

        $booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => $checkInDate,
            'duration_months' => 1,
            'room_subtotal' => 1000000,
            'admin_fee' => 2500,
            'commission_fee' => 50000,
            'net_owner_amount' => 950000,
            'total_price' => 1002500,
            'status' => 'Active',
            'payment_status' => 'Paid',
            'escrow_status' => 'held',
        ]);

        // Assert starting room availability
        $this->assertEquals(3, $this->roomType->fresh()->available_rooms);

        // Run the artisan command
        $this->artisan('bookings:auto-complete')
            ->expectsOutput("Scanning for expired active bookings as of today: " . now()->toDateString())
            ->expectsOutput("Processing expired booking ID {$booking->id} (Checkout date: " . Carbon::parse($checkInDate)->addMonth()->toDateString() . ")")
            ->expectsOutput("Escrow payout successful for Booking ID {$booking->id}. Ref: iris-ref-test-auto-999")
            ->assertExitCode(0);

        // Assert booking is updated to Completed and escrow status is released
        $booking->refresh();
        $this->assertEquals('Completed', $booking->status);
        $this->assertEquals('released', $booking->escrow_status);
        $this->assertEquals('success', $booking->payout_status);
        $this->assertEquals('iris-ref-test-auto-999', $booking->payout_reference);

        // Assert room availability is incremented back from 3 to 4
        $this->assertEquals(4, $this->roomType->fresh()->available_rooms);
    }

    /**
     * Test active bookings that are NOT expired are untouched.
     */
    public function test_does_not_complete_non_expired_active_bookings(): void
    {
        // Active booking check-in = today, duration = 1 month, so checkout is next month (not expired)
        $checkInDate = Carbon::now()->toDateString();

        $booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => $checkInDate,
            'duration_months' => 1,
            'room_subtotal' => 1000000,
            'admin_fee' => 2500,
            'commission_fee' => 50000,
            'net_owner_amount' => 950000,
            'total_price' => 1002500,
            'status' => 'Active',
            'payment_status' => 'Paid',
            'escrow_status' => 'held',
        ]);

        $this->artisan('bookings:auto-complete')
            ->expectsOutput("Scanning for expired active bookings as of today: " . now()->toDateString())
            ->expectsOutput("No expired active bookings found.")
            ->assertExitCode(0);

        $booking->refresh();
        $this->assertEquals('Active', $booking->status);
        $this->assertEquals('held', $booking->escrow_status);
        $this->assertEquals(3, $this->roomType->fresh()->available_rooms);
    }
}

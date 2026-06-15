<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use App\Mail\SendRentExtensionReminderEmail;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendRentExtensionRemindersTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $seeker;
    protected $property;
    protected $roomType;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Owner
        $this->owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
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
            'available_rooms' => 4,
            'description' => 'Room description',
        ]);
    }

    /**
     * Test reminders are sent for active bookings ending in exactly 7 days.
     */
    public function test_sends_reminder_for_booking_ending_in_seven_days(): void
    {
        Mail::fake();

        // Target Date is 7 days from now
        // So stay checkout_date must be Carbon::now()->addDays(7)
        // Check-in date = checkout_date - 1 month
        $checkInDate = Carbon::now()->addDays(7)->subMonth()->toDateString();
        $expectedCheckoutDate = Carbon::now()->addDays(7)->toDateString();

        $booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => $checkInDate,
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'Active',
            'payment_status' => 'Paid',
        ]);

        // Run the artisan command
        $this->artisan('bookings:send-reminders')
            ->expectsOutput("Scanning for active bookings ending on: {$expectedCheckoutDate}")
            ->expectsOutput("Created new renewal booking ID " . ($booking->id + 1) . " for User ID {$this->seeker->id} starting on {$expectedCheckoutDate}.")
            ->expectsOutput("Extension reminder email sent to {$this->seeker->email} for Booking ID {$booking->id}.")
            ->assertExitCode(0);

        // Assert renewal booking is created in database
        $this->assertTrue(
            Booking::where('user_id', $this->seeker->id)
                ->where('room_type_id', $this->roomType->id)
                ->whereDate('check_in_date', $expectedCheckoutDate)
                ->where('duration_months', 1)
                ->where('total_price', 1000000)
                ->where('status', 'Pending')
                ->where('payment_status', 'Unpaid')
                ->exists()
        );

        // Assert Mail was queued
        Mail::assertQueued(SendRentExtensionReminderEmail::class, function ($mail) use ($booking) {
            return $mail->hasTo($this->seeker->email) &&
                   $mail->booking->id === $booking->id &&
                   $mail->renewalBooking->payment_status === 'Unpaid';
        });
    }

    /**
     * Test reminders are not sent if booking ends in 8 days.
     */
    public function test_does_not_send_reminder_for_booking_ending_in_eight_days(): void
    {
        Mail::fake();

        $checkInDate = Carbon::now()->addDays(8)->subMonth()->toDateString();

        Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => $checkInDate,
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'Active',
            'payment_status' => 'Paid',
        ]);

        $expectedTargetDate = Carbon::now()->addDays(7)->toDateString();

        $this->artisan('bookings:send-reminders')
            ->expectsOutput("Scanning for active bookings ending on: {$expectedTargetDate}")
            ->expectsOutput("No active bookings ending on {$expectedTargetDate} found.")
            ->assertExitCode(0);

        // Assert no renewal booking is created
        $this->assertEquals(1, Booking::count());

        Mail::assertNothingQueued();
    }

    /**
     * Test running command twice does not duplicate renewal bookings or emails,
     * but sends reminder for the unpaid renewal if already exists.
     */
    public function test_running_command_twice_sends_reminder_for_existing_unpaid_renewal(): void
    {
        Mail::fake();

        $checkInDate = Carbon::now()->addDays(7)->subMonth()->toDateString();
        $expectedCheckoutDate = Carbon::now()->addDays(7)->toDateString();

        $booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => $checkInDate,
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'Active',
            'payment_status' => 'Paid',
        ]);

        // Create the unpaid renewal beforehand
        $renewalBooking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => $expectedCheckoutDate,
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
        ]);

        $this->artisan('bookings:send-reminders')
            ->expectsOutput("Scanning for active bookings ending on: {$expectedCheckoutDate}")
            ->expectsOutput("Unpaid renewal booking ID {$renewalBooking->id} already exists for User ID {$this->seeker->id}. Sending reminder.")
            ->expectsOutput("Extension reminder email sent to {$this->seeker->email} for Booking ID {$booking->id}.")
            ->assertExitCode(0);

        // Booking count should still be 2 (no new booking created)
        $this->assertEquals(2, Booking::count());

        Mail::assertQueued(SendRentExtensionReminderEmail::class, 1);
    }
}

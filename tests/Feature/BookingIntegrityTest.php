<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $seeker;
    protected $property;
    protected $roomType;
    protected $booking;
    protected $serverKey;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serverKey = 'Mid-server-test-key-12345';
        config(['services.midtrans.server_key' => $this->serverKey]);

        // Create Owner
        $this->owner = User::create([
            'name' => 'Owner Jane',
            'email' => 'owner.jane@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        // Create Seeker
        $this->seeker = User::create([
            'name' => 'Seeker Joe',
            'email' => 'seeker.joe@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        // Create Property
        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Indah',
            'slug' => 'kos-indah',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Pendidikan No. 5',
            'latitude' => '-8.587063',
            'longitude' => '116.092185',
            'description' => 'Kos indah dekat UNRAM',
            'main_image' => 'properties/indah.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        // Create RoomType
        $this->roomType = RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Kamar Deluxe',
            'price_per_month' => 1200000,
            'total_rooms' => 5,
            'available_rooms' => 5,
        ]);

        // Create Booking
        $this->booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 1202500,
            'room_subtotal' => 1200000,
            'admin_fee' => 2500,
            'commission_fee' => 60000,
            'net_owner_amount' => 1140000,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
        ]);
    }

    /**
     * Test Double-Decrement Guard in BookingController::verify
     */
    public function test_verify_prevents_double_verification_and_double_decrement(): void
    {
        $this->actingAs($this->owner);

        // Verify first time
        $response1 = $this->post(route('booking.verify', $this->booking));
        $response1->assertRedirect();
        $response1->assertSessionHas('success', 'Pembayaran berhasil diverifikasi. Booking sekarang aktif.');

        $this->booking->refresh();
        $this->roomType->refresh();

        $this->assertEquals('Active', $this->booking->status);
        $this->assertEquals('Paid', $this->booking->payment_status);
        $this->assertEquals(4, $this->roomType->available_rooms); // Decremented once (5 -> 4)

        // Verify second time
        $response2 = $this->post(route('booking.verify', $this->booking));
        $response2->assertRedirect();
        $response2->assertSessionHas('error', 'Pemesanan ini sudah diverifikasi sebelumnya.');

        $this->roomType->refresh();
        $this->assertEquals(4, $this->roomType->available_rooms); // Still 4 (no double decrement!)
    }

    /**
     * Test Webhook Settlement guard for Cancelled bookings
     */
    public function test_webhook_settlement_does_not_activate_cancelled_booking(): void
    {
        // Seeker cancels pending booking manually
        $this->booking->update([
            'status' => 'Cancelled',
            'payment_status' => 'Unpaid',
        ]);

        // Mock Midtrans Webhook Notification payload
        $orderId = $this->booking->id . '-' . $this->booking->updated_at->timestamp;
        $statusCode = '200';
        $grossAmount = '1202500.00';
        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson(route('payment.notification'), $payload);
        $response->assertStatus(200);

        $this->booking->refresh();
        $this->roomType->refresh();

        // Status must remain Cancelled, though payment status changes to Paid
        $this->assertEquals('Cancelled', $this->booking->status);
        $this->assertEquals('Paid', $this->booking->payment_status);
        $this->assertEquals(5, $this->roomType->available_rooms); // Room count not decremented!
    }

    /**
     * Test Webhook Expiration guard for Completed bookings
     */
    public function test_webhook_expiration_does_not_cancel_completed_booking(): void
    {
        // Booking completed manually
        $this->booking->update([
            'status' => 'Completed',
            'payment_status' => 'Paid',
        ]);

        // Mock Midtrans Webhook Notification payload for expire
        $orderId = $this->booking->id . '-' . $this->booking->updated_at->timestamp;
        $statusCode = '200';
        $grossAmount = '1202500.00';
        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'expire',
        ];

        $response = $this->postJson(route('payment.notification'), $payload);
        $response->assertStatus(200);

        $this->booking->refresh();

        // Status must remain Completed and Paid (not changed to Cancelled)
        $this->assertEquals('Completed', $this->booking->status);
        $this->assertEquals('Paid', $this->booking->payment_status);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransWebhookTest extends TestCase
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

        // Setup credentials config
        $this->serverKey = 'Mid-server-test-key-12345';
        config(['services.midtrans.server_key' => $this->serverKey]);

        // Create models
        $this->owner = User::create([
            'name' => 'Owner Jane',
            'email' => 'owner.jane@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->seeker = User::create([
            'name' => 'Seeker Joe',
            'email' => 'seeker.joe@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Indah',
            'slug' => 'kos-indah',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Pendidikan No. 5',
            'latitude' => '-8.5878',
            'longitude' => '116.0967',
            'description' => 'Kos indah dekat UNRAM',
            'main_image' => 'properties/indah.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        $this->roomType = RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Single Bed',
            'price_per_month' => 1000000,
            'total_rooms' => 5,
            'available_rooms' => 3,
            'description' => 'Single bed room type',
        ]);

        $this->booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 1000000,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'payment_token' => 'snap-token-123',
        ]);
    }

    /**
     * Test webhook fails on invalid or missing payload structure.
     */
    public function test_webhook_validation_fails_on_missing_payload(): void
    {
        $response = $this->postJson(route('payment.notification'), []);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'Invalid notification payload']);
    }

    /**
     * Test webhook fails when the signature key does not match.
     */
    public function test_webhook_validation_fails_on_invalid_signature(): void
    {
        $payload = [
            'order_id' => $this->booking->id,
            'status_code' => '200',
            'gross_amount' => '1000000.00',
            'signature_key' => 'invalid-signature-key-value',
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson(route('payment.notification'), $payload);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'Invalid signature key']);
    }

    /**
     * Test successful payment settlement updates booking status and decrements inventory.
     */
    public function test_webhook_settles_payment_and_decrements_room_inventory(): void
    {
        $orderId = $this->booking->id;
        $statusCode = '200';
        $grossAmount = '1000000.00';
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signatureKey,
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson(route('payment.notification'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Notification processed successfully']);

        // Assert booking updated
        $this->assertDatabaseHas('bookings', [
            'id' => $this->booking->id,
            'payment_status' => 'Paid',
            'status' => 'Active',
        ]);

        // Assert room inventory decremented
        $this->assertDatabaseHas('room_types', [
            'id' => $this->roomType->id,
            'available_rooms' => 2, // 3 - 1
        ]);
    }

    /**
     * Test overbooking prevention when room inventory is depleted before settlement.
     */
    public function test_webhook_handles_overbooking_when_room_inventory_is_depleted(): void
    {
        // Force available rooms to 0
        $this->roomType->update(['available_rooms' => 0]);

        $orderId = $this->booking->id;
        $statusCode = '200';
        $grossAmount = '1000000.00';
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signatureKey,
            'transaction_status' => 'settlement',
        ];

        $response = $this->postJson(route('payment.notification'), $payload);

        $response->assertStatus(200);

        // Assert booking status set to Cancelled due to lack of inventory
        $this->assertDatabaseHas('bookings', [
            'id' => $this->booking->id,
            'payment_status' => 'Paid',
            'status' => 'Cancelled',
        ]);

        // Assert available rooms remain 0
        $this->assertDatabaseHas('room_types', [
            'id' => $this->roomType->id,
            'available_rooms' => 0,
        ]);
    }

    /**
     * Test webhook handles cancellation and restores inventory if previous booking was active.
     */
    public function test_webhook_handles_cancellation_and_restores_room_inventory(): void
    {
        // First, simulate active booking that already decremented inventory
        $this->booking->update([
            'payment_status' => 'Paid',
            'status' => 'Active',
        ]);
        $this->roomType->update(['available_rooms' => 2]); // Manual adjustment for state

        $orderId = $this->booking->id;
        $statusCode = '200';
        $grossAmount = '1000000.00';
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signatureKey,
            'transaction_status' => 'cancel', // cancel / expire / deny
        ];

        $response = $this->postJson(route('payment.notification'), $payload);

        $response->assertStatus(200);

        // Assert booking updated to Cancelled/Unpaid
        $this->assertDatabaseHas('bookings', [
            'id' => $this->booking->id,
            'payment_status' => 'Unpaid',
            'status' => 'Cancelled',
        ]);

        // Assert inventory restored back to 3 (2 + 1)
        $this->assertDatabaseHas('room_types', [
            'id' => $this->roomType->id,
            'available_rooms' => 3,
        ]);
    }

    /**
     * Test pending status keeps booking unpaid and sets status to Pending.
     */
    public function test_webhook_handles_pending_transaction(): void
    {
        $orderId = $this->booking->id;
        $statusCode = '201'; // Pending status code usually
        $grossAmount = '1000000.00';
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $this->serverKey);

        $payload = [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signatureKey,
            'transaction_status' => 'pending',
        ];

        $response = $this->postJson(route('payment.notification'), $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('bookings', [
            'id' => $this->booking->id,
            'payment_status' => 'Unpaid',
            'status' => 'Pending',
        ]);
    }

    /**
     * Test booking creation endpoint calculates and stores monetization breakdown.
     */
    public function test_booking_creation_calculates_monetization_fields(): void
    {
        $this->actingAs($this->seeker);

        $response = $this->post(route('booking.store'), [
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2)->format('Y-m-d'),
            'duration_months' => 3,
        ]);

        $response->assertRedirect();
        
        $this->assertEquals(2, Booking::count(), 'Booking was not created');
        
        $booking = Booking::where('user_id', $this->seeker->id)->orderBy('id', 'desc')->first();
        
        $expectedSubtotal = $this->roomType->price_per_month * 3; // 3000000
        $expectedAdminFee = 2500;
        $expectedCommission = (int) round($expectedSubtotal * 0.05); // 150000
        $expectedNet = $expectedSubtotal - $expectedCommission; // 2850000
        $expectedTotal = $expectedSubtotal + $expectedAdminFee; // 3002500

        $this->assertEquals($expectedSubtotal, $booking->room_subtotal);
        $this->assertEquals($expectedAdminFee, $booking->admin_fee);
        $this->assertEquals($expectedCommission, $booking->commission_fee);
        $this->assertEquals($expectedNet, $booking->net_owner_amount);
        $this->assertEquals($expectedTotal, $booking->total_price);
    }
}

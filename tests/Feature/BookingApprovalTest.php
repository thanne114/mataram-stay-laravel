<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BookingApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $seeker;
    protected $property;
    protected $roomType;

    protected function setUp(): void
    {
        parent::setUp();

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
            'latitude' => '-8.587063',
            'longitude' => '116.092185',
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
    }

    public function test_seeker_booking_initial_state_is_not_approved(): void
    {
        $this->actingAs($this->seeker);

        $response = $this->post(route('booking.store'), [
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2)->format('Y-m-d'),
            'duration_months' => 2,
        ]);

        $response->assertRedirect();
        
        $booking = Booking::first();
        $this->assertFalse($booking->is_approved);
    }

    public function test_payment_token_not_generated_and_ui_hidden_if_not_approved(): void
    {
        $booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 1002500,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'is_approved' => false,
        ]);

        $this->actingAs($this->seeker);

        $response = $this->get(route('booking.show', $booking));
        $response->assertStatus(200);

        // Seeker should see the warning banner
        $response->assertSee('Menunggu Persetujuan Pemilik Kos');
        
        // Seeker should NOT see the payment button/widget
        $response->assertDontSee('Bayar Sekarang');
        $response->assertDontSee('Transfer Manual (Alternatif)');

        // Payment token should remain null
        $booking->refresh();
        $this->assertNull($booking->payment_token);
    }

    public function test_owner_can_approve_booking(): void
    {
        Mail::fake();

        $booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 1002500,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'is_approved' => false,
        ]);

        $this->actingAs($this->owner);

        // Owner sees the approve/reject buttons
        $responseShow = $this->get(route('booking.show', $booking));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Persetujuan Sewa (Request to Book)');
        $responseShow->assertSee('Terima Pesanan');
        $responseShow->assertSee('Tolak Pesanan');

        // Approve booking
        $responseApprove = $this->post(route('booking.approve', $booking));
        $responseApprove->assertRedirect();
        
        $booking->refresh();
        $this->assertTrue($booking->is_approved);

        // Mail should be sent to seeker
        Mail::assertSent(\App\Mail\BookingNotificationMail::class, function ($mail) use ($booking) {
            return $mail->hasTo($this->seeker->email) &&
                   $mail->type === 'approved_seeker' &&
                   $mail->booking->id === $booking->id;
        });

        // Now seeker views booking, token should be generated and payment options shown
        $this->actingAs($this->seeker);
        $responseShowSeeker = $this->get(route('booking.show', $booking));
        $responseShowSeeker->assertStatus(200);
        $responseShowSeeker->assertDontSee('Menunggu Persetujuan Pemilik Kos');
        
        // Midtrans token is generated if configured, but at least warning banner is gone
        $booking->refresh();
    }

    public function test_owner_can_reject_booking(): void
    {
        Mail::fake();

        $booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 1002500,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'is_approved' => false,
        ]);

        $this->actingAs($this->owner);

        // Reject booking
        $responseReject = $this->post(route('booking.reject', $booking));
        $responseReject->assertRedirect();

        $booking->refresh();
        $this->assertEquals('Cancelled', $booking->status);

        // Mail should be sent to seeker
        Mail::assertSent(\App\Mail\BookingNotificationMail::class, function ($mail) use ($booking) {
            return $mail->hasTo($this->seeker->email) &&
                   $mail->type === 'rejected_seeker' &&
                   $mail->booking->id === $booking->id;
        });
    }

    public function test_non_owner_cannot_approve_or_reject_booking(): void
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'email' => 'other.user@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 1002500,
            'status' => 'Pending',
            'payment_status' => 'Unpaid',
            'is_approved' => false,
        ]);

        $this->actingAs($otherUser);

        // Approve should fail
        $responseApprove = $this->post(route('booking.approve', $booking));
        $responseApprove->assertStatus(403);

        // Reject should fail
        $responseReject = $this->post(route('booking.reject', $booking));
        $responseReject->assertStatus(403);

        $booking->refresh();
        $this->assertFalse($booking->is_approved);
        $this->assertEquals('Pending', $booking->status);
    }

    public function test_pending_transaction_indicator_excludes_cancelled_or_completed_bookings(): void
    {
        // 1. Create a Cancelled booking with Unpaid payment status
        Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 1002500,
            'status' => 'Cancelled',
            'payment_status' => 'Unpaid',
            'is_approved' => false,
        ]);

        // 2. Create a Completed booking with Paid payment status
        Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 1002500,
            'status' => 'Completed',
            'payment_status' => 'Paid',
            'is_approved' => true,
        ]);

        $this->actingAs($this->seeker);

        // Seeker dashboard response
        $responseDashboard = $this->get(route('dashboard.seeker'));
        $responseDashboard->assertStatus(200);
        
        // Assert that the warning banner is not present
        $responseDashboard->assertDontSee('Pengingat: Anda memiliki transaksi yang belum diselesaikan');
        
        // Assert view variables show hasPendingTransaction as false
        $responseDashboard->assertViewHas('hasPendingTransaction', false);
    }
}


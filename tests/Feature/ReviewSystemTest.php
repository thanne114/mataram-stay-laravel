<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $seeker;
    protected $otherSeeker;
    protected $property;
    protected $roomType;
    protected $booking;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Owner
        $this->owner = User::create([
            'name' => 'Owner Al',
            'email' => 'owner.al@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        // Create Seeker
        $this->seeker = User::create([
            'name' => 'Seeker Budi',
            'email' => 'seeker.budi@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        // Create Other Seeker
        $this->otherSeeker = User::create([
            'name' => 'Seeker Ani',
            'email' => 'seeker.ani@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        // Create Property
        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Baru',
            'slug' => 'kos-baru',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Baru No. 1',
            'latitude' => '-8.587063',
            'longitude' => '116.092185',
            'description' => 'Kos baru bersih',
            'main_image' => 'properties/baru.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        // Create Room Type
        $this->roomType = RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Kamar Standard',
            'price_per_month' => 800000,
            'total_rooms' => 5,
            'available_rooms' => 3,
            'description' => 'Standard room',
        ]);

        // Create Booking
        $this->booking = Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => '2026-06-01',
            'duration_months' => 1,
            'total_price' => 800000,
            'status' => 'Completed',
            'payment_status' => 'Paid',
        ]);
    }

    /**
     * Test seeker can successfully submit a review for completed and paid booking.
     */
    public function test_seeker_can_submit_review_for_completed_and_paid_booking(): void
    {
        $this->actingAs($this->seeker);

        $response = $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 5,
            'comment' => 'Kos sangat nyaman!',
        ]);

        $response->assertStatus(302); // Redirect back
        $this->assertDatabaseHas('reviews', [
            'booking_id' => $this->booking->id,
            'user_id' => $this->seeker->id,
            'rating' => 5,
            'comment' => 'Kos sangat nyaman!',
        ]);
    }

    /**
     * Test seeker cannot review unpaid or pending booking.
     */
    public function test_seeker_cannot_review_unpaid_or_pending_booking(): void
    {
        $this->actingAs($this->seeker);

        // Change status to Active but unpaid
        $this->booking->update([
            'status' => 'Active',
            'payment_status' => 'Unpaid',
        ]);

        $response = $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 5,
            'comment' => 'Nyaman',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $this->booking->id,
        ]);
    }

    /**
     * Test seeker cannot review other users' booking.
     */
    public function test_seeker_cannot_review_other_users_booking(): void
    {
        $this->actingAs($this->otherSeeker); // ani tries to review budi's booking

        $response = $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 4,
            'comment' => 'Mencoba review',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('reviews', [
            'booking_id' => $this->booking->id,
        ]);
    }

    /**
     * Test seeker cannot duplicate reviews for the same booking.
     */
    public function test_seeker_cannot_duplicate_reviews(): void
    {
        $this->actingAs($this->seeker);

        // First review
        $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 5,
            'comment' => 'Bagus',
        ]);

        // Second review
        $response = $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 4,
            'comment' => 'Ulang review',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(1, \App\Models\Review::where('booking_id', $this->booking->id)->count());
    }

    /**
     * Test owner is unauthorized to submit reviews.
     */
    public function test_owner_cannot_submit_reviews(): void
    {
        $this->actingAs($this->owner);

        $response = $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 5,
            'comment' => 'Bagus',
        ]);

        $response->assertStatus(403); // FormRequest authorize returns false
    }

    /**
     * Test validation bounds for rating and comment.
     */
    public function test_review_validation_bounds(): void
    {
        $this->actingAs($this->seeker);

        // Test rating too high
        $response = $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 6,
            'comment' => 'Bagus',
        ]);
        $response->assertSessionHasErrors('rating');

        // Test rating too low
        $response = $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 0,
            'comment' => 'Bagus',
        ]);
        $response->assertSessionHasErrors('rating');

        // Test comment too long (1001 chars)
        $response = $this->post('/review', [
            'booking_id' => $this->booking->id,
            'rating' => 5,
            'comment' => str_repeat('a', 1001),
        ]);
        $response->assertSessionHasErrors('comment');
    }

    /**
     * Test transactions page shows "Beri Ulasan" button for Completed & Paid seeker bookings without review.
     */
    public function test_transactions_page_shows_beri_ulasan_button_appropriately(): void
    {
        $this->actingAs($this->seeker);

        // Scenario 1: Completed & Paid booking with NO review -> should show button
        $response = $this->get('/seeker/transactions');
        $response->assertStatus(200);
        $response->assertSee('Beri Ulasan');

        // Scenario 2: If booking has a review -> should NOT show button
        \App\Models\Review::create([
            'booking_id' => $this->booking->id,
            'property_id' => $this->property->id,
            'user_id' => $this->seeker->id,
            'rating' => 5,
            'comment' => 'Bagus',
        ]);

        $response = $this->get('/seeker/transactions');
        $response->assertStatus(200);
        $response->assertDontSee('Beri Ulasan');
    }

    /**
     * Test property page displays average rating and reviews list.
     */
    public function test_property_page_displays_average_rating_and_reviews(): void
    {
        // First create a review
        \App\Models\Review::create([
            'booking_id' => $this->booking->id,
            'property_id' => $this->property->id,
            'user_id' => $this->seeker->id,
            'rating' => 5,
            'comment' => 'Tempat yang sangat bersih!',
        ]);

        $response = $this->get('/kos/' . $this->property->slug);
        $response->assertStatus(200);
        // Should show "Ulasan & Rating" section heading
        $response->assertSee('Ulasan');
        $response->assertSee('Rating');
        // Should show average rating
        $response->assertSee('5');
        // Should show total reviews count
        $response->assertSee('1 Ulasan');
        // Should show comments
        $response->assertSee('Tempat yang sangat bersih!');
    }
}

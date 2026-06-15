<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrgencyAndSocialProofTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $property;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Kebon UNRAM',
            'slug' => 'kos-kebon-unram',
            'type' => 'Campur',
            'area' => 'Selaparang',
            'address' => 'Jln. Kebon No. 4, Mataram',
            'latitude' => '-8.5880', // Very close to UNRAM (-8.5878, 116.0967)
            'longitude' => '116.0970',
            'description' => 'Kos dekat kampus',
            'main_image' => 'properties/test.png',
            'is_verified' => true,
            'status' => 'published',
        ]);
    }

    /**
     * Test closest campus logic finds UNRAM.
     */
    public function test_calculates_closest_campus_proximity(): void
    {
        $closest = $this->property->closest_campus;

        $this->assertNotEmpty($closest);
        $this->assertEquals('Universitas Mataram (UNRAM)', $closest['name']);
        $this->assertLessThan(0.1, $closest['distance']); // distance should be very small
        $this->assertStringContainsString('Menit jalan kaki', $closest['label']);
    }

    /**
     * Test views count is calculated deterministically.
     */
    public function test_calculates_views_count_deterministically(): void
    {
        $viewsCount = $this->property->views_count;
        $this->assertGreaterThanOrEqual(8, $viewsCount);
        $this->assertLessThanOrEqual(35, $viewsCount);

        // Assert it remains same within same request/time
        $this->assertEquals($viewsCount, $this->property->views_count);
    }

    /**
     * Test search results page shows urgent stock label when 1-3 rooms are available.
     */
    public function test_shows_urgent_stock_warning_when_low_availability(): void
    {
        // Add Room Type with 2 available rooms (low stock)
        RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Kamar Standar',
            'price_per_month' => 800000,
            'total_rooms' => 5,
            'available_rooms' => 2,
            'description' => 'Standar room',
        ]);

        $response = $this->get('/search');
        $response->assertStatus(200);
        $response->assertSee('Sisa 2 Kamar Lagi!');
        $response->assertSee('Dilihat ' . $this->property->views_count . ' kali');
        $response->assertSee('jalan kaki ke Universitas Mataram');
    }

    /**
     * Test search results page shows standard label when availability is > 3 rooms.
     */
    public function test_shows_standard_stock_label_when_high_availability(): void
    {
        // Add Room Type with 5 available rooms (normal stock)
        RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Kamar Standar',
            'price_per_month' => 800000,
            'total_rooms' => 10,
            'available_rooms' => 5,
            'description' => 'Standar room',
        ]);

        $response = $this->get('/search');
        $response->assertStatus(200);
        $response->assertSee('5 Kamar Tersedia');
        $response->assertDontSee('Sisa 5 Kamar Lagi!');
    }
}

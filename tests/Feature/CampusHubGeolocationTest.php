<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampusHubGeolocationTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::create([
            'name' => 'Owner Jane',
            'email' => 'owner.jane@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);
    }

    /**
     * Test closest campus returns the default closest campus based on distance.
     */
    public function test_closest_campus_returns_default_closest(): void
    {
        // Kos placed extremely close to UNRAM (-8.587063, 116.092185)
        $property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos UNRAM Dekat',
            'slug' => 'kos-unram-dekat',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Perjuangan No. 1',
            'latitude' => '-8.588000',
            'longitude' => '116.093000',
            'is_verified' => true,
            'status' => 'published',
        ]);

        $closest = $property->closest_campus;

        $this->assertNotEmpty($closest);
        $this->assertEquals('Universitas Mataram (UNRAM)', $closest['name']);
        $this->assertLessThan(0.5, $closest['distance']); // Less than 500m
        $this->assertStringContainsString('Universitas Mataram (UNRAM)', $closest['label']);
    }

    /**
     * Test closest campus respects the active request parameter to display selected campus distance.
     */
    public function test_closest_campus_respects_request_parameter(): void
    {
        // Kos placed close to UNRAM
        $property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos UNRAM Dekat',
            'slug' => 'kos-unram-dekat',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Perjuangan No. 1',
            'latitude' => '-8.588000',
            'longitude' => '116.093000',
            'is_verified' => true,
            'status' => 'published',
        ]);

        // Inject request parameters to simulate ?kampus=UMMAT
        request()->merge(['kampus' => 'UMMAT']);

        $closest = $property->closest_campus;

        $this->assertNotEmpty($closest);
        // Should calculate distance specifically to UMMAT instead of the closest UNRAM
        $this->assertEquals('Universitas Muhammadiyah Mataram (UMMAT)', $closest['name']);
        // Clean up request state
        request()->replace([]);
    }

    /**
     * Test nearby campuses returns list of campuses within 3km sorted by distance.
     */
    public function test_nearby_campuses_returns_campuses_within_3km(): void
    {
        $property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Tengah Kota',
            'slug' => 'kos-tengah-kota',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Pendidikan',
            'latitude' => '-8.587063',
            'longitude' => '116.092185',
            'is_verified' => true,
            'status' => 'published',
        ]);

        $nearby = $property->nearby_campuses;

        $this->assertNotEmpty($nearby);
        // The closest should be UNRAM (distance 0.0)
        $this->assertEquals('UNRAM', $nearby[0]['key']);
        $this->assertEquals(0.0, $nearby[0]['distance']);
        
        // Assert all listed campuses are within 3 km
        foreach ($nearby as $campus) {
            $this->assertLessThanOrEqual(3.0, $campus['distance']);
        }
    }

    /**
     * Test search results route correctly filters properties by selected campus hub radius.
     */
    public function test_search_results_filtering_by_campus(): void
    {
        // 1. Property A: Near UNRAM (distance < 3km)
        $propertyA = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Dekat UNRAM',
            'slug' => 'kos-dekat-unram',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Belakang Kampus',
            'latitude' => '-8.587500',
            'longitude' => '116.093000',
            'is_verified' => true,
            'status' => 'published',
        ]);

        // 2. Property B: Far from UNRAM (distance > 3km, placed far east)
        $propertyB = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Jauh UNRAM',
            'slug' => 'kos-jauh-unram',
            'type' => 'Campur',
            'area' => 'Cakranegara',
            'address' => 'Jln. Cakra Raya',
            'latitude' => '-8.620000',
            'longitude' => '116.150000',
            'is_verified' => true,
            'status' => 'published',
        ]);

        // Query search results filtering by UNRAM
        $response = $this->get(route('search', ['kampus' => 'UNRAM']));

        $response->assertStatus(200);
        $response->assertSee('Kos Dekat UNRAM');
        $response->assertDontSee('Kos Jauh UNRAM');
    }
}

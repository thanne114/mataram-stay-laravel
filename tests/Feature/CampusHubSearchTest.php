<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampusHubSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $propertyNearUnram;
    protected $propertyFarFromUnram;

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

        // Property near UNRAM (-8.587063, 116.092185)
        $this->propertyNearUnram = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Dekat UNRAM',
            'slug' => 'kos-dekat-unram',
            'type' => 'Putra',
            'area' => 'Mataram',
            'address' => 'Jln. Pendidikan No. 5',
            'latitude' => '-8.5871',
            'longitude' => '116.0922',
            'description' => 'Kos putra dekat UNRAM',
            'main_image' => 'properties/near.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        RoomType::create([
            'property_id' => $this->propertyNearUnram->id,
            'name' => 'Kamar Standard',
            'price_per_month' => 800000,
            'total_rooms' => 5,
            'available_rooms' => 3,
            'description' => 'Standard room',
        ]);

        // Property far from UNRAM (e.g. in Cakranegara area, more than 3km away)
        $this->propertyFarFromUnram = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Jauh di Cakra',
            'slug' => 'kos-jauh-cakra',
            'type' => 'Putri',
            'area' => 'Cakranegara',
            'address' => 'Jln. Pejanggik No. 100',
            'latitude' => '-8.5900',
            'longitude' => '116.1400', // far east of UNRAM
            'description' => 'Kos putri di Cakra',
            'main_image' => 'properties/far.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        RoomType::create([
            'property_id' => $this->propertyFarFromUnram->id,
            'name' => 'Kamar VIP',
            'price_per_month' => 1800000,
            'total_rooms' => 3,
            'available_rooms' => 2,
            'description' => 'VIP room',
        ]);
    }

    /**
     * Test searching by lokasi (area).
     */
    public function test_can_filter_by_lokasi(): void
    {
        $response = $this->get('/search?lokasi=Mataram');

        $response->assertStatus(200);
        $response->assertSee('Kos Dekat UNRAM');
        $response->assertDontSee('Kos Jauh di Cakra');
    }

    /**
     * Test searching by tipe kos.
     */
    public function test_can_filter_by_tipe_kos(): void
    {
        $response = $this->get('/search?tipe_kos=Putri');

        $response->assertStatus(200);
        $response->assertSee('Kos Jauh di Cakra');
        $response->assertDontSee('Kos Dekat UNRAM');
    }

    /**
     * Test searching by max price.
     */
    public function test_can_filter_by_max_price(): void
    {
        $response = $this->get('/search?harga_maksimal=1000000');

        $response->assertStatus(200);
        $response->assertSee('Kos Dekat UNRAM');
        $response->assertDontSee('Kos Jauh di Cakra'); // price is 1.8M
    }

    /**
     * Test search by campus hub (radius query).
     */
    public function test_can_filter_by_campus_hub_radius(): void
    {
        $response = $this->get('/search?kampus=UNRAM');

        $response->assertStatus(200);
        $response->assertSee('Kos Dekat UNRAM');
        $response->assertDontSee('Kos Jauh di Cakra');
    }

    /**
     * Test searching by facilities.
     */
    public function test_can_filter_by_facilities(): void
    {
        // Create facilities
        $wifi = \App\Models\Facility::create(['name' => 'WiFi', 'icon' => 'wifi']);
        $ac = \App\Models\Facility::create(['name' => 'AC', 'icon' => 'ac_unit']);

        // Attach WiFi to propertyNearUnram, both WiFi and AC to propertyFarFromUnram
        $this->propertyNearUnram->facilities()->attach($wifi->id);
        $this->propertyFarFromUnram->facilities()->attach([$wifi->id, $ac->id]);

        // Request with WiFi only
        $response = $this->get('/search?fasilitas[]=' . $wifi->id);
        $response->assertStatus(200);
        $response->assertSee('Kos Dekat UNRAM');
        $response->assertSee('Kos Jauh di Cakra');

        // Request with AC only
        $response = $this->get('/search?fasilitas[]=' . $ac->id);
        $response->assertStatus(200);
        $response->assertDontSee('Kos Dekat UNRAM');
        $response->assertSee('Kos Jauh di Cakra');

        // Request with both WiFi and AC
        $response = $this->get('/search?fasilitas[]=' . $wifi->id . '&fasilitas[]=' . $ac->id);
        $response->assertStatus(200);
        $response->assertDontSee('Kos Dekat UNRAM');
        $response->assertSee('Kos Jauh di Cakra');
    }

    /**
     * Test detail page contains dynamic SEO tags and dynamic alt attributes.
     */
    public function test_detail_page_has_dynamic_seo_tags_and_alt_attributes(): void
    {
        $property = $this->propertyNearUnram;

        // Generate images for gallery testing
        $property->images()->create(['image_path' => 'properties/gallery/test1.png']);

        $response = $this->get('/kos/' . $property->slug);
        $response->assertStatus(200);

        // Check dynamic title
        $response->assertSee($property->name . ' - Mataram Stay');

        // Check dynamic description limit
        $expectedDesc = \Illuminate\Support\Str::limit($property->description, 150, '');
        $response->assertSee($expectedDesc);

        // Check dynamic image alt text
        $response->assertSee('alt="Foto utama ' . $property->name . '"', false);
        $response->assertSee('alt="Foto fasilitas ' . $property->name . '"', false);
    }

    /**
     * Test sitemap directory page is accessible and lists correct content.
     */
    public function test_kampus_directory_page_is_accessible(): void
    {
        $response = $this->get('/kampus');

        $response->assertStatus(200);
        $response->assertSee('Kos Dekat Kampus di Mataram');
        $response->assertSee('Perguruan Tinggi Negeri (PTN)');
        $response->assertSee('Perguruan Tinggi Swasta (PTS)');

        // Check PTN campus links
        $response->assertSee('Kos Dekat UNRAM Mataram');
        $response->assertSee('Kos Dekat UIN Mataram');
        $response->assertSee('Kos Dekat Poltekkes Kemenkes Mataram');
        $response->assertSee('Kos Dekat UT Mataram');

        // Check PTS campus links
        $response->assertSee('Kos Dekat UMMAT Mataram');
        $response->assertSee('Kos Dekat UTM Mataram');
        $response->assertSee('Kos Dekat UNBIM Mataram');
        $response->assertSee('Kos Dekat IAHN Gde Pudja Mataram');
        $response->assertSee('Kos Dekat INKES Yarsi Mataram');
        $response->assertSee('Kos Dekat STIKES Mataram');
        $response->assertSee('Kos Dekat Universitas Mahasaraswati Mataram');
    }
}

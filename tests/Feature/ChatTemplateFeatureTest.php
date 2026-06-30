<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTemplateFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $seeker;
    protected $owner;
    protected $property;
    protected $roomType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seeker = User::create([
            'name' => 'Seeker User',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $this->owner = User::create([
            'name' => 'Owner User',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Mapala',
            'slug' => 'kos-mapala',
            'type' => 'Campur',
            'area' => 'Rappocini',
            'address' => 'Jl. Mapala Raya No. 12',
            'latitude' => '-8.6000',
            'longitude' => '116.1000',
            'status' => 'published',
        ]);

        $this->roomType = RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Standard Room',
            'price_per_month' => 1600000,
            'total_rooms' => 5,
            'available_rooms' => 3,
        ]);
    }

    public function test_seeker_starts_chat_with_custom_template_message(): void
    {
        $response = $this->actingAs($this->seeker)->post(route('chat.start', $this->property), [
            'message' => 'Saya butuh cepat nih. Bisa booking sekarang?',
        ]);

        // Expect redirect to profile edit with conversation_id
        $conversation = Conversation::where('seeker_id', $this->seeker->id)
            ->where('owner_id', $this->owner->id)
            ->where('property_id', $this->property->id)
            ->first();

        $this->assertNotNull($conversation);
        $response->assertRedirect(route('profile.edit', ['tab' => 'view-pesan', 'conversation_id' => $conversation->id]));

        // Seeker message saved
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $this->seeker->id,
            'body' => 'Saya butuh cepat nih. Bisa booking sekarang?',
        ]);

        // Auto-reply message from owner saved
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $this->owner->id,
            'body' => "Balasan otomatis: Halo! Silakan ajukan sewa melalui tombol 'Ajukan Sewa' yang tersedia di atas. Kami akan segera memproses pengajuan Anda.",
        ]);
    }

    public function test_seeker_asks_about_address_receives_dynamic_auto_reply(): void
    {
        $response = $this->actingAs($this->seeker)->post(route('chat.start', $this->property), [
            'message' => 'Alamat kos di mana?',
        ]);

        $conversation = Conversation::first();

        // Auto-reply message contains dynamic property address
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $this->owner->id,
            'body' => "Balasan otomatis: Halo! Alamat lengkap kos kami adalah: Jl. Mapala Raya No. 12. Anda juga dapat melihat lokasi persisnya pada peta interaktif di detail properti.",
        ]);
    }

    public function test_seeker_asks_about_non_template_quick_message(): void
    {
        $response = $this->actingAs($this->seeker)->post(route('chat.start', $this->property), [
            'message' => 'Halo apakah ada diskon?',
        ]);

        $conversation = Conversation::first();

        // Seeker message exists
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $this->seeker->id,
            'body' => 'Halo apakah ada diskon?',
        ]);

        // No auto-reply generated since the message didn't match the exact quick templates
        $ownerMessagesCount = Message::where('conversation_id', $conversation->id)
            ->where('sender_id', $this->owner->id)
            ->count();
        $this->assertEquals(0, $ownerMessagesCount);
    }
}

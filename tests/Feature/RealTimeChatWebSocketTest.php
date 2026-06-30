<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Property;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;

class RealTimeChatWebSocketTest extends TestCase
{
    use RefreshDatabase;

    protected $seeker;
    protected $owner;
    protected $property;
    protected $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure dummy Pusher credentials to allow local auth signature generation
        config([
            'broadcasting.default' => 'pusher',
            'broadcasting.connections.pusher.key' => 'dummy_key',
            'broadcasting.connections.pusher.secret' => 'dummy_secret',
            'broadcasting.connections.pusher.app_id' => 'dummy_id',
            'broadcasting.connections.pusher.options.cluster' => 'mt1',
        ]);

        // Resolve driver and re-require channels file to register channels on the newly active driver
        Broadcast::driver('pusher');
        require base_path('routes/channels.php');

        $this->seeker = User::create([
            'name' => 'Seeker User',
            'username' => 'seeker_user',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
            'no_whatsapp' => '08123456781',
        ]);

        $this->owner = User::create([
            'name' => 'Owner User',
            'username' => 'owner_user',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'no_whatsapp' => '08123456782',
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Test Kos',
            'slug' => 'test-kos',
            'type' => 'Campur',
            'area' => 'Ampenan',
            'address' => 'Test Address',
            'description' => 'Test Description',
            'latitude' => -8.5,
            'longitude' => 116.1,
            'status' => 'Aktif',
            'lowest_price' => 500000,
        ]);

        $this->conversation = Conversation::create([
            'seeker_id' => $this->seeker->id,
            'owner_id' => $this->owner->id,
            'property_id' => $this->property->id,
        ]);
    }

    /**
     * Test sending a chat message dispatches the MessageSent broadcast event.
     */
    public function test_sending_message_dispatches_messagesent_broadcast_event(): void
    {
        Event::fake([MessageSent::class]);

        $this->actingAs($this->seeker);

        $response = $this->postJson(route('chat.send', $this->conversation), [
            'body' => 'Halo Pemilik Kos, apakah masih ada kamar?'
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        Event::assertDispatched(MessageSent::class, function ($event) {
            return $event->message->body === 'Halo Pemilik Kos, apakah masih ada kamar?'
                && (int)$event->message->conversation_id === (int)$this->conversation->id
                && (int)$event->message->sender_id === (int)$this->seeker->id;
        });
    }

    /**
     * Test channel authentication logic for participants.
     */
    public function test_conversation_channel_authorization_allows_participants(): void
    {
        $this->actingAs($this->seeker);

        $response = $this->postJson('/broadcasting/auth', [
            'channel_name' => 'private-conversation.' . $this->conversation->id,
            'socket_id' => '12345.67890'
        ]);

        $this->assertEquals(200, $response->status(), "Seeker auth failed. Response: " . $response->getContent());
        $response->assertJsonStructure(['auth']);

        $this->actingAs($this->owner);

        $responseOwner = $this->postJson('/broadcasting/auth', [
            'channel_name' => 'private-conversation.' . $this->conversation->id,
            'socket_id' => '12345.67890'
        ]);

        $this->assertEquals(200, $responseOwner->status(), "Owner auth failed. Response: " . $responseOwner->getContent());
        $responseOwner->assertJsonStructure(['auth']);
    }

    /**
     * Test channel authentication logic denies non-participants.
     */
    public function test_conversation_channel_authorization_denies_non_participants(): void
    {
        $otherUser = User::create([
            'name' => 'Other User',
            'username' => 'other_user',
            'email' => 'other@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
            'no_whatsapp' => '08123456783',
        ]);

        $this->actingAs($otherUser);

        $response = $this->postJson('/broadcasting/auth', [
            'channel_name' => 'private-conversation.' . $this->conversation->id,
            'socket_id' => '12345.67890'
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test ChatController@start only allows seekers to initiate conversation.
     */
    public function test_only_seekers_can_start_chat_with_owner(): void
    {
        $this->actingAs($this->seeker);
        $response1 = $this->post(route('chat.start', $this->property));
        $response1->assertRedirect();

        $otherOwner = User::create([
            'name' => 'Other Owner',
            'username' => 'other_owner',
            'email' => 'other_owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);
        $this->actingAs($otherOwner);
        $response2 = $this->post(route('chat.start', $this->property));
        $response2->assertSessionHas('error', 'Hanya pencari kos yang dapat memulai obrolan.');
    }
}

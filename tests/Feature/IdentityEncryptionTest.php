<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IdentityEncryptionTest extends TestCase
{
    use RefreshDatabase;

    protected $seeker;
    protected $anotherUser;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seeker = User::create([
            'name' => 'Seeker User',
            'username' => 'seeker_user',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
            'no_whatsapp' => '08123456789',
        ]);

        $this->anotherUser = User::create([
            'name' => 'Another User',
            'username' => 'another_user',
            'email' => 'another@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'no_whatsapp' => '08123456789',
        ]);

        $this->admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_user',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'no_whatsapp' => '08123456789',
        ]);

        Storage::fake('local');
    }

    /**
     * Test uploading documents encrypts files and downloading decrypts them correctly.
     */
    public function test_document_upload_encryption_and_decryption(): void
    {
        $this->actingAs($this->seeker);

        $fakeKtp = UploadedFile::fake()->create('ktp.png', 100, 'image/png');
        $fakeSelfie = UploadedFile::fake()->create('selfie.jpg', 100, 'image/jpeg');

        $response = $this->from('/profile')->post('/profile/verify-identity', [
            'identity_type' => 'ktp',
            'identity_photo' => $fakeKtp,
            'selfie_photo' => $fakeSelfie,
        ]);

        $response->assertRedirect('/profile');
        $response->assertSessionHas('success');

        // Reload user
        $this->seeker->refresh();

        $this->assertNotNull($this->seeker->identity_photo);
        $this->assertNotNull($this->seeker->selfie_photo);

        // Verify files exist in fake local storage
        Storage::disk('local')->assertExists($this->seeker->identity_photo);
        Storage::disk('local')->assertExists($this->seeker->selfie_photo);

        // Verify the raw file content on disk is encrypted
        $rawFileContent = Storage::disk('local')->get($this->seeker->identity_photo);
        $this->assertNotEquals($fakeKtp->get(), $rawFileContent); // should be different due to encryption
        $this->assertEquals($fakeKtp->get(), Crypt::decryptString($rawFileContent)); // should decrypt back to original

        // Verify dynamic decryption access for owner (seeker)
        $filename = basename($this->seeker->identity_photo);
        $downloadResponse = $this->get("/profile/identity-photo/{$filename}");
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertHeader('Content-Type', 'image/png');
        $this->assertEquals($fakeKtp->get(), $downloadResponse->getContent());

        // Verify dynamic decryption access for admin
        $this->actingAs($this->admin);
        $downloadResponseAdmin = $this->get("/profile/identity-photo/{$filename}");
        $downloadResponseAdmin->assertStatus(200);
        $downloadResponseAdmin->assertHeader('Content-Type', 'image/png');
        $this->assertEquals($fakeKtp->get(), $downloadResponseAdmin->getContent());

        // Verify access is blocked (403) for another user
        $this->actingAs($this->anotherUser);
        $downloadResponseBlocked = $this->get("/profile/identity-photo/{$filename}");
        $downloadResponseBlocked->assertStatus(403);
    }
}

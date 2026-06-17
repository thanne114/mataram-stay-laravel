<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_bantuan_page_is_accessible(): void
    {
        $response = $this->get('/bantuan');

        $response->assertStatus(200);
        $response->assertSee('Pusat Bantuan');
    }

    public function test_wisata_page_is_accessible(): void
    {
        $response = $this->get('/wisata');

        $response->assertStatus(200);
        $response->assertSee('Wisata Lombok');
    }

    public function test_syarat_ketentuan_page_is_accessible(): void
    {
        $response = $this->get('/syarat-ketentuan');

        $response->assertStatus(200);
        $response->assertSee('Syarat & Ketentuan', false);
    }

    public function test_kebijakan_privasi_page_is_accessible(): void
    {
        $response = $this->get('/kebijakan-privasi');

        $response->assertStatus(200);
        $response->assertSee('Kebijakan Privasi');
    }

    public function test_tentang_page_is_accessible(): void
    {
        $response = $this->get('/tentang');

        $response->assertStatus(200);
        $response->assertSee('Tentang Mataram Stay');
    }

    public function test_blog_page_is_accessible(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Blog & Artikel', false);
    }
}

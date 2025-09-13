<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Office;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class OfficeApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_all_offices()
    {
        Office::factory()->count(3)->create();

        $response = $this->getJson('/api/offices');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    #[Test]
    public function it_shows_single_office()
    {
        $office = Office::factory()->create();

        $response = $this->getJson("/api/offices/{$office->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $office->name]);
    }

    #[Test]
    public function it_creates_office()
    {
        $data = ['name' => 'New Office'];

        $response = $this->postJson('/api/offices', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment($data);

        $this->assertDatabaseHas('offices', $data);
    }

    #[Test]
    public function it_updates_office()
    {
        $office = Office::factory()->create(['name' => 'Old Name']);
        $data = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/offices/{$office->id}", $data);

        $response->assertStatus(200)
                 ->assertJsonFragment($data);

        $this->assertDatabaseHas('offices', $data);
    }

    #[Test]
    public function it_deletes_office()
    {
        $office = Office::factory()->create();

        $response = $this->deleteJson("/api/offices/{$office->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Office deleted']);

        $this->assertDatabaseMissing('offices', ['id' => $office->id]);
    }
}

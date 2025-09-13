<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Income;
use App\Models\Office;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class IncomeApiTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    #[Test]
    public function it_returns_all_incomes()
    {
        $this->actingUser();
        $income = Income::factory()->create();
        $response = $this->getJson('/api/incomes');

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $income->id]);
    }

    #[Test]
    public function it_shows_single_income()
    {
        $this->actingUser();
        $income = Income::factory()->create();

        $response = $this->getJson("/api/incomes/{$income->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $income->id]);
    }

    #[Test]
    public function it_creates_income()
    {
        $this->actingUser();
        $office = Office::factory()->create();
        $article = Article::factory()->create();

        $payload = [
            'description' => 'Test Income',
            'amount' => 500,
            'office_id' => $office->id,
            'article_id' => $article->id,
        ];

        $response = $this->postJson('/api/incomes', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['description' => 'Test Income']);

        $this->assertDatabaseHas('incomes', ['description' => 'Test Income']);
    }

    #[Test]
    public function it_updates_income()
    {
        $this->actingUser();
        $income = Income::factory()->create(['description' => 'Old']);

        $response = $this->putJson("/api/incomes/{$income->id}", [
            'description' => 'Updated'
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['description' => 'Updated']);

        $this->assertDatabaseHas('incomes', ['description' => 'Updated']);
    }

    #[Test]
    public function it_deletes_income()
    {
        $this->actingUser();
        $income = Income::factory()->create();

        $response = $this->deleteJson("/api/incomes/{$income->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Income deleted']);

        $this->assertDatabaseMissing('incomes', ['id' => $income->id]);
    }
}

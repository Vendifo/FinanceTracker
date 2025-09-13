<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ExpenseApiTest extends TestCase
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
    public function can_list_expenses()
    {
        $this->actingUser();
        Expense::factory()->count(3)->create();

        $response = $this->getJson('/api/expenses');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[Test]
    public function can_create_expense()
    {
        $user = $this->actingUser();
        $article = \App\Models\Article::factory()->create();
        $office = \App\Models\Office::factory()->create();

        $response = $this->postJson('/api/expenses', [
            'description' => 'API Expense',
            'amount'      => 100,
            'user_id'     => $user->id,
            'article_id'  => $article->id,
            'office_id'   => $office->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['description' => 'API Expense']);

        $this->assertDatabaseHas('expenses', ['description' => 'API Expense']);
    }


    #[Test]
    public function can_show_expense()
    {
        $this->actingUser();
        $expense = Expense::factory()->create();

        $response = $this->getJson("/api/expenses/{$expense->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $expense->id]);
    }

    #[Test]
    public function can_update_expense()
    {
        $this->actingUser();
        $expense = Expense::factory()->create(['description' => 'Old']);

        $response = $this->putJson("/api/expenses/{$expense->id}", ['description' => 'Updated']);

        $response->assertStatus(200)
            ->assertJsonFragment(['description' => 'Updated']);

        $this->assertDatabaseHas('expenses', ['description' => 'Updated']);
    }

    #[Test]
    public function can_delete_expense()
    {
        $this->actingUser();
        $expense = Expense::factory()->create();

        $response = $this->deleteJson("/api/expenses/{$expense->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Expense deleted']);

        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    #[Test]
    public function returns_404_for_missing_expense()
    {
        $this->actingUser();

        $response = $this->getJson('/api/expenses/999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Expense not found']);
    }
}

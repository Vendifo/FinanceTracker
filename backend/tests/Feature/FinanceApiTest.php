<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Office;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class FinanceApiTest extends TestCase
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
    public function it_returns_financial_summary()
    {
        $this->actingUser();

        $office = Office::factory()->create();
        $article = Article::factory()->create();

        Income::factory()->create([
            'amount' => 1000,
            'office_id' => $office->id,
            'article_id' => $article->id,
            'created_at' => now(),
        ]);

        Expense::factory()->create([
            'amount' => 500,
            'office_id' => $office->id,
            'article_id' => $article->id,
            'created_at' => now(),
        ]);

        $response = $this->getJson("/api/finance/summary?office_id={$office->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'income' => 1000,
                'expense' => 500,
                'balance' => 500,
            ]);
    }


    #[Test]
public function it_returns_incomes_and_expenses_for_index()
{
    $this->actingUser();
    $office = Office::factory()->create();
    $article = Article::factory()->create();

    Income::factory()->create([
        'amount' => 100,
        'office_id' => $office->id,
        'article_id' => $article->id,
        'created_at' => now(),
    ]);

    Expense::factory()->create([
        'amount' => 50,
        'office_id' => $office->id,
        'article_id' => $article->id,
        'created_at' => now(),
    ]);

    $response = $this->getJson('/api/finance?office_id=' . $office->id);

    $response->assertStatus(200)
        ->assertJsonFragment(['balance' => 50]);
}


    #[Test]
    public function it_returns_balance_by_period()
    {
        $user = $this->actingUser();
        $office = Office::factory()->create();
        $article = Article::factory()->create();

        Income::factory()->create([
            'amount' => 300,
            'office_id' => $office->id,
            'article_id' => $article->id,
            'created_at' => now(),
        ]);

        Expense::factory()->create([
            'amount' => 100,
            'office_id' => $office->id,
            'article_id' => $article->id,
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/finance/balance-period?office_id=' . $office->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['income' => 300, 'expense' => 100, 'balance' => 200]);
    }

    #[Test]
    public function it_returns_by_office_and_by_article()
{
    $user = $this->actingUser();
    $office = Office::factory()->create();
    $article = Article::factory()->create();
    $today = now()->format('Y-m-d');

    Income::factory()->create([
        'amount' => 200,
        'office_id' => $office->id,
        'article_id' => $article->id,
        'created_at' => $today,
    ]);

    Expense::factory()->create([
        'amount' => 50,
        'office_id' => $office->id,
        'article_id' => $article->id,
        'user_id' => $user->id,
        'created_at' => $today,
    ]);

    $this->actingAs($user);

    $responseOffice = $this->getJson('/api/finance/by-office?office_id=' . $office->id);
    $responseArticle = $this->getJson('/api/finance/by-article?office_id=' . $office->id);

    $responseOffice->assertStatus(200)
        ->assertJsonPath('offices.0.balance', 150);

    $responseArticle->assertStatus(200)
        ->assertJsonPath('offices.0.balance', 150);
}


}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domain\Expense\Services\ExpenseService;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ExpenseServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ExpenseService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ExpenseService::class);
    }

    #[Test]
    public function it_can_create_expense()
    {
        $user = User::factory()->create();
        $article = \App\Models\Article::factory()->create();
        $office = \App\Models\Office::factory()->create();

        $expense = $this->service->create([
            'description' => 'Test Expense',
            'amount'      => 100,
            'user_id'     => $user->id,
            'article_id'  => $article->id,
            'office_id'   => $office->id,
        ]);

        $this->assertInstanceOf(Expense::class, $expense);
        $this->assertDatabaseHas('expenses', ['description' => 'Test Expense']);
    }


    #[Test]
    public function it_can_find_expense()
    {
        $expense = Expense::factory()->create();
        $found = $this->service->find($expense->id);

        $this->assertNotNull($found);
        $this->assertEquals($expense->id, $found->id);
    }

    #[Test]
    public function it_can_update_expense()
    {
        $expense = Expense::factory()->create(['description' => 'Old']);
        $updated = $this->service->update($expense->id, ['description' => 'Updated']);

        $this->assertEquals('Updated', $updated->description);
        $this->assertDatabaseHas('expenses', ['description' => 'Updated']);
    }

    #[Test]
    public function it_can_delete_expense()
    {
        $expense = Expense::factory()->create();
        $result = $this->service->delete($expense->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}

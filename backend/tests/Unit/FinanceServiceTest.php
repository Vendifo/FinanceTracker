<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domain\Finance\Services\FinanceService;
use App\Domain\Finance\Repositories\FinanceRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Mockery;

class FinanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected FinanceService $service;
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(FinanceRepositoryInterface::class);
        $this->service = new FinanceService($this->repository);
    }

    #[Test]
    public function it_returns_total_income()
    {
        $filters = ['office_id' => 1];
        $this->repository->shouldReceive('totalIncome')->with($filters)->once()->andReturn(1000.0);

        $this->assertEquals(1000.0, $this->service->totalIncome($filters));
    }

    #[Test]
    public function it_returns_total_expense()
    {
        $filters = ['office_id' => 1];
        $this->repository->shouldReceive('totalExpense')->with($filters)->once()->andReturn(500.0);

        $this->assertEquals(500.0, $this->service->totalExpense($filters));
    }

    #[Test]
    public function it_returns_balance()
    {
        $filters = ['office_id' => 1];
        $this->repository->shouldReceive('balance')->with($filters)->once()->andReturn(500.0);

        $this->assertEquals(500.0, $this->service->balance($filters));
    }

    #[Test]
public function it_calls_repository_methods_for_incomes_expenses_and_period()
{
    $filters = ['office_id' => 1];

    $this->repository->shouldReceive('incomes')->with($filters)->once()->andReturn([]);
    $this->repository->shouldReceive('expenses')->with($filters)->once()->andReturn([]);
    $this->repository->shouldReceive('balanceByPeriod')->with($filters)->once()->andReturn([]);
    $this->repository->shouldReceive('byOffice')->with($filters)->once()->andReturn([]);
    $this->repository->shouldReceive('byArticle')->with($filters)->once()->andReturn([]);

    $this->service->incomes($filters);
    $this->service->expenses($filters);
    $this->service->balanceByPeriod($filters);
    $this->service->byOffice($filters);
    $this->service->byArticle($filters);

    // Проверяем, что методы действительно вызваны
    $this->repository->shouldHaveReceived('incomes')->with($filters)->once();
    $this->repository->shouldHaveReceived('expenses')->with($filters)->once();
    $this->repository->shouldHaveReceived('balanceByPeriod')->with($filters)->once();
    $this->repository->shouldHaveReceived('byOffice')->with($filters)->once();
    $this->repository->shouldHaveReceived('byArticle')->with($filters)->once();
}

}

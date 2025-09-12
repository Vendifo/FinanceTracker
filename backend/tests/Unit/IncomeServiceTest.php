<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domain\Income\Services\IncomeService;
use App\Domain\Income\Repositories\IncomeRepositoryInterface;
use App\Models\Income;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class IncomeServiceTest extends TestCase
{
    /** @var \Mockery\MockInterface|IncomeRepositoryInterface */
    protected $repository;

    protected IncomeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(IncomeRepositoryInterface::class);
        $this->service = new IncomeService($this->repository);
    }

    #[Test]
    public function it_returns_all_incomes()
    {
        $this->repository->shouldReceive('all')->once()->andReturn(['income1', 'income2']);
        $this->assertCount(2, $this->service->all());
    }

    #[Test]
    public function it_finds_income_by_id()
    {
        $this->repository->shouldReceive('find')->with(1)->once()->andReturn('income1');
        $this->assertEquals('income1', $this->service->find(1));
    }

    #[Test]
    public function it_creates_income()
    {
        $data = ['amount' => 100];
        $this->repository->shouldReceive('create')->with($data)->once()->andReturn('income1');
        $this->assertEquals('income1', $this->service->create($data));
    }

    #[Test]
    public function it_updates_income()
    {
        $data = ['amount' => 150];
        $income = new Income(['id' => 1, 'amount' => 100]);

        $this->repository->shouldReceive('find')->with(1)->once()->andReturn($income);
        $this->repository->shouldReceive('update')->with($income, $data)->once()->andReturn($income);

        $this->assertEquals($income, $this->service->update(1, $data));
    }

    #[Test]
    public function it_deletes_income()
    {
        $income = new Income(['id' => 1]);

        $this->repository->shouldReceive('find')->with(1)->once()->andReturn($income);
        $this->repository->shouldReceive('delete')->with($income)->once()->andReturn(true);

        $this->assertTrue($this->service->delete(1));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Domain\Office\Services\OfficeService;
use App\Domain\Office\Repositories\OfficeRepositoryInterface;
use App\Models\Office;
use PHPUnit\Framework\Attributes\Test;

class OfficeServiceTest extends TestCase
{
    /** @var \Mockery\MockInterface|OfficeRepositoryInterface */
    protected $repository;

    protected OfficeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(OfficeRepositoryInterface::class);
        $this->service = new OfficeService($this->repository);
    }

    #[Test]
    public function it_returns_all_offices()
    {
        $offices = [new Office(['name' => 'A']), new Office(['name' => 'B'])];
        /** @noinspection PhpUndefinedMethodInspection */
        $this->repository->shouldReceive('all')->once()->andReturn($offices);

        $this->assertCount(2, $this->service->all());
    }

    #[Test]
    public function it_finds_office_by_id()
    {
        $office = new Office(['id' => 1, 'name' => 'A']);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->repository->shouldReceive('find')->with(1)->once()->andReturn($office);

        $this->assertSame($office, $this->service->find(1));
    }

    #[Test]
    public function it_creates_office()
    {
        $data = ['name' => 'New Office'];
        $office = new Office($data);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->repository->shouldReceive('create')->with($data)->once()->andReturn($office);

        $this->assertSame($office, $this->service->create($data));
    }

    #[Test]
    public function it_updates_office()
    {
        $data = ['name' => 'Updated'];
        $office = new Office(['id' => 1, 'name' => 'Old']);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->repository->shouldReceive('find')->with(1)->once()->andReturn($office);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->repository->shouldReceive('update')->with($office, $data)->once()->andReturn($office);

        $this->assertSame($office, $this->service->update(1, $data));
    }

    #[Test]
    public function it_deletes_office()
    {
        $office = new Office(['id' => 1]);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->repository->shouldReceive('find')->with(1)->once()->andReturn($office);
        /** @noinspection PhpUndefinedMethodInspection */
        $this->repository->shouldReceive('delete')->with($office)->once()->andReturn(true);

        $this->assertTrue($this->service->delete(1));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

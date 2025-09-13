<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Domain\Role\Services\RoleService;
use App\Domain\Role\Repositories\RoleRepositoryInterface;
use App\Models\Role;
use PHPUnit\Framework\Attributes\Test;

class RoleServiceTest extends TestCase
{
    /** @var \Mockery\MockInterface|RoleRepositoryInterface */
    protected $repository;

    protected RoleService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = Mockery::mock(RoleRepositoryInterface::class);
        $this->service = new RoleService($this->repository);
    }

    #[Test]
    public function it_returns_all_roles()
    {
        $roles = [new Role(['name' => 'Admin']), new Role(['name' => 'User'])];
        $this->repository->shouldReceive('all')->once()->andReturn($roles);

        $this->assertCount(2, $this->service->all());
    }

    #[Test]
    public function it_finds_role_by_id()
    {
        $role = new Role(['id' => 1, 'name' => 'Admin']);
        $this->repository->shouldReceive('find')->with(1)->once()->andReturn($role);

        $this->assertSame($role, $this->service->find(1));
    }

    #[Test]
    public function it_creates_role()
    {
        $data = ['name' => 'Editor'];
        $role = new Role($data);
        $this->repository->shouldReceive('create')->with($data)->once()->andReturn($role);

        $this->assertSame($role, $this->service->create($data));
    }

    #[Test]
    public function it_updates_role()
    {
        $data = ['name' => 'Moderator'];
        $role = new Role(['id' => 1, 'name' => 'Old']);
        $this->repository->shouldReceive('find')->with(1)->once()->andReturn($role);
        $this->repository->shouldReceive('update')->with($role, $data)->once()->andReturn($role);

        $this->assertSame($role, $this->service->update(1, $data));
    }

    #[Test]
    public function it_deletes_role()
    {
        $role = new Role(['id' => 1]);
        $this->repository->shouldReceive('find')->with(1)->once()->andReturn($role);
        $this->repository->shouldReceive('delete')->with($role)->once()->andReturn(true);

        $this->assertTrue($this->service->delete(1));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

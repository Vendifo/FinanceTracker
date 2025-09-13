<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;

class RoleApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var \App\Models\User $user */

        // Создаем пользователя и авторизуем его для тестов
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); // или 'api' в зависимости от guard
    }

    #[Test]
    public function it_returns_all_roles()
    {
        Role::factory()->count(3)->create();

        $response = $this->getJson('/api/roles');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function it_shows_single_role()
    {
        $role = Role::factory()->create();

        $response = $this->getJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $role->name]);
    }

    #[Test]
    public function it_creates_role()
    {
        $data = ['name' => 'New Role'];

        $response = $this->postJson('/api/roles', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('roles', $data);
    }

    #[Test]
    public function it_updates_role()
    {
        $role = Role::factory()->create();
        $data = ['name' => 'Updated Role'];

        $response = $this->putJson("/api/roles/{$role->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('roles', $data);
    }

    #[Test]
    public function it_deletes_role()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Role deleted']);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}

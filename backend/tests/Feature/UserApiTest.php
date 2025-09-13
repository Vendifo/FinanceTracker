<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем пользователя для аутентификации
        $user = User::factory()->create();
        /** @var \App\Models\User $user */
        $this->actingAs($user, 'sanctum');
    }

    public function test_index_returns_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'first_name',
                        'last_name',
                        'role',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'message'
            ]);
    }

    public function test_store_creates_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123'
        ];

        $response = $this->postJson('/api/users', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'john@example.com']);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_show_returns_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $user->id]);
    }

    public function test_update_modifies_user()
    {
        $user = User::factory()->create();
        $data = ['first_name' => 'UpdatedFirstName'];

        $response = $this->putJson("/api/users/{$user->id}", $data);

        $response->assertStatus(200)
            ->assertJsonPath('data.first_name', 'UpdatedFirstName');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'first_name' => 'UpdatedFirstName']);
    }



    public function test_destroy_deletes_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}

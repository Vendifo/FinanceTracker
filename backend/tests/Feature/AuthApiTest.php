<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function can_register_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'created_at', 'updated_at']
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    #[Test]
    public function cannot_register_with_existing_email()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422); // validation error
    }

    #[Test]
    public function can_login_user()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email', 'created_at', 'updated_at'],
                    'token'
                ]
            ]);
    }

    #[Test]
    public function cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid email or password', 'success' => false]);
    }

    #[Test]
    public function can_get_current_user()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/current');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Current user',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email
                    ]
                ]
            ]);
    }

    #[Test]
    public function cannot_get_me_if_not_authenticated()
    {
        $response = $this->getJson('/api/current');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    #[Test]
    public function can_logout_user()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => 'Logout successful']);
    }

    #[Test]
    public function cannot_logout_if_not_authenticated()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}

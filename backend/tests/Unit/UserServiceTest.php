<?php

namespace Tests\Unit;

use App\Domain\User\Services\UserService;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = app(UserService::class);
    }

    public function test_create_hashes_password()
    {
        $data = ['name' => 'John', 'email' => 'john@example.com', 'password' => 'secret123'];

        $user = $this->userService->create($data);

        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_assign_role()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $user = $this->userService->assignRole($user, $role->id);

        $this->assertEquals($role->id, $user->role->id);
    }

    public function test_change_password_success()
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);

        $result = $this->userService->changePassword($user, [
            'current_password' => 'oldpassword',
            'new_password' => 'newpassword'
        ]);

        $this->assertTrue($result);
        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }

    public function test_change_password_throws_exception_if_current_password_wrong()
{
    $this->expectException(\Illuminate\Validation\ValidationException::class);

    /** @var User $user */
    $user = User::factory()->create(['password' => Hash::make('oldpassword')]);

    // авторизация пользователя
    $this->actingAs($user);

    $this->userService->changePassword($user, [
        'current_password' => 'wrongpassword',
        'new_password' => 'newpassword'
    ]);
}

}

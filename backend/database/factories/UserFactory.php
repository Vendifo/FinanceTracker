<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $roleIds = Role::pluck('id')->toArray(); // все существующие роли

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $middleName = $this->faker->optional()->firstName;

        return [
            'name' => $firstName . ' ' . $lastName,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // стандартный пароль для всех
            'role_id' => $this->faker->randomElement($roleIds),
            'remember_token' => Str::random(10),
        ];
    }

    // Удобные состояния
    public function user(): Factory
    {
        $roleId = Role::where('name', 'user')->first()->id;
        return $this->state(fn () => ['role_id' => $roleId]);
    }

    public function manager(): Factory
    {
        $roleId = Role::where('name', 'manager')->first()->id;
        return $this->state(fn () => ['role_id' => $roleId]);
    }

    public function accountant(): Factory
    {
        $roleId = Role::where('name', 'accountant')->first()->id;
        return $this->state(fn () => ['role_id' => $roleId]);
    }
}

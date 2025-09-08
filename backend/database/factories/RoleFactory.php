<?php

namespace Database\Factories;


use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['user', 'manager', 'accountant']),
        ];
    }

    public function user(): Factory
    {
        return $this->state(fn () => ['name' => 'user']);
    }

    public function manager(): Factory
    {
        return $this->state(fn () => ['name' => 'manager']);
    }

    public function accountant(): Factory
    {
        return $this->state(fn () => ['name' => 'accountant']);
    }
}

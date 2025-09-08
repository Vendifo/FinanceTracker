<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Создать 20 случайных пользователей с ролями
        User::factory()->count(20)->create();

        // Опционально: создать администратора/менеджера/бухгалтера с фиксированными данными
        User::factory()->manager()->create([
            'name' => 'Manager One',
            'email' => 'manager@example.com',
        ]);

        User::factory()->accountant()->create([
            'name' => 'Accountant One',
            'email' => 'accountant@example.com',
        ]);

        User::factory()->user()->create([
            'name' => 'User One',
            'email' => 'user@example.com',
        ]);
    }
}

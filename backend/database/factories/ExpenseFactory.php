<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use App\Models\Office;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(5),
            'amount' => $this->faker->randomFloat(2, 500, 20000),
            'user_id' => User::factory(),        // по умолчанию создаёт нового пользователя
            'article_id' => null,                // задаём через тест
            'office_id' => null,                 // задаём через тест
            'created_at' => $this->faker->dateTimeBetween('2025-01-01', '2025-12-31'),
            'updated_at' => now(),
        ];
    }
}


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
        $userIds = User::pluck('id')->toArray();
        $articleIds = Article::pluck('id')->toArray();
        $officeIds = Office::pluck('id')->toArray();

        return [
            'description' => $this->faker->sentence(5),
            'amount' => $this->faker->randomFloat(2, 500, 20000),
            'user_id' => $this->faker->randomElement($userIds),
            'article_id' => $this->faker->randomElement($articleIds),
            'office_id' => $this->faker->randomElement($officeIds),
            'created_at' => $this->faker->dateTimeBetween('2025-01-01', '2025-12-31'),
            'updated_at' => now(),
        ];
    }
}

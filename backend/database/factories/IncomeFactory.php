<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\Office;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition(): array
    {
        $articleIds = Article::pluck('id')->toArray();
        $officeIds = Office::pluck('id')->toArray();

        return [
            'description' => $this->faker->sentence(5),
            'amount' => $this->faker->randomFloat(2, 1000, 50000), // суммы от 1к до 50к
            'article_id' => $this->faker->randomElement($articleIds),
            'office_id' => $this->faker->randomElement($officeIds),
            'created_at' => $this->faker->dateTimeBetween('2025-01-01', '2025-12-31'),
            'updated_at' => now(),
        ];
    }
}

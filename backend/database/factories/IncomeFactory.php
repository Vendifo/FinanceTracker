<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\User;
use App\Models\Office;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    protected $model = Income::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(5),
            'amount' => $this->faker->randomFloat(2, 1000, 50000),
            'article_id' => Article::factory(),   // всегда создаём артикул
            'office_id' => Office::factory(),     // всегда создаём офис
            'created_at' => $this->faker->dateTimeBetween('2025-01-01', '2025-12-31'),
            'updated_at' => now(),
        ];
    }
}

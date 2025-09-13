<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Income;
use App\Models\Office;
use App\Models\Article;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $totalRecords = 10000;

        // Создаём фиксированное количество офисов и статей
        $offices = Office::factory()->count(10)->create();
        $articles = Article::factory()->count(50)->create();

        // Создаём доходы
        Income::factory()->count($totalRecords)->make()->each(function ($income) use ($offices, $articles) {
            $income->office_id = $offices->random()->id;
            $income->article_id = $articles->random()->id;
            $income->save();
        });
    }
}

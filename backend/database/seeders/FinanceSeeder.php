<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Office;
use App\Models\Article;
use App\Models\Expense;
use App\Models\Income;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // Создаём пользователей, офисы и статьи
        $users = User::factory()->count(50)->create();
        $offices = Office::factory()->count(10)->create();
        $articles = Article::factory()->count(50)->create();

        $totalRecords = 10000;

        // Генерация расходов
        Expense::factory()->count($totalRecords)->make()->each(function ($expense) use ($users, $offices, $articles) {
            $expense->user_id    = $users->random()->id;
            $expense->office_id  = $offices->random()->id;
            $expense->article_id = $articles->random()->id;
            $expense->save();
        });

        // Генерация доходов
        Income::factory()->count($totalRecords)->make()->each(function ($income) use ($offices, $articles) {
            $income->office_id  = $offices->random()->id;
            $income->article_id = $articles->random()->id;
            $income->save();
        });
    }
}

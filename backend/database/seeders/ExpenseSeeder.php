<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Office;
use App\Models\Article;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        // Создаём ограниченное количество пользователей, офисов и статей
        $users = User::factory()->count(50)->create();
        $offices = Office::factory()->count(10)->create();
        $articles = Article::factory()->count(50)->create();

        // Создаём 10 000 расходов, используя случайные уже созданные записи
        Expense::factory()->count(10000)->make()->each(function ($expense) use ($users, $offices, $articles) {
            $expense->user_id = $users->random()->id;
            $expense->office_id = $offices->random()->id;
            $expense->article_id = $articles->random()->id;
            $expense->save();
        });
    }
}

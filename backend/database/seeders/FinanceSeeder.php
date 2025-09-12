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
        $users = User::all();
        $offices = Office::all();
        $articles = Article::all();

        $totalRecords = 1000;

        for ($i = 0; $i < $totalRecords; $i++) {
            $user = $users->random();
            $office = $offices->random();
            $article = $articles->random();

            $incomeAmount = rand(0, 50000) / 100;
            $expenseAmount = rand(0, 20000) / 100;

            // Доход
            Income::create([
                'office_id'  => $office->id,
                'article_id' => $article->id,
                'amount'     => $incomeAmount,
                'description'=> "Доход по статье {$article->name} для {$user->name}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Расход
            Expense::create([
                'user_id'    => $user->id,
                'office_id'  => $office->id,
                'article_id' => $article->id,
                'amount'     => $expenseAmount,
                'description'=> "Расход по статье {$article->name} для {$user->name}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

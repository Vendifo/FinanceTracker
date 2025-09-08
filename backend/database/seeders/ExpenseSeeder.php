<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $totalRecords = 10000;

        Expense::factory()->count($totalRecords)->create();
    }
}

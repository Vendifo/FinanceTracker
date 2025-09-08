<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Income;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $totalRecords = 10000;

        Income::factory()->count($totalRecords)->create();
    }
}

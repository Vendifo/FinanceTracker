<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    $this->call([
        RoleSeeder::class,
        UserSeeder::class,
        OfficeSeeder::class,
        ArticleSeeder::class,
        IncomeSeeder::class,
        ExpenseSeeder::class,
    ]);
}

}

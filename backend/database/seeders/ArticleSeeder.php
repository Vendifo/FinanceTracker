<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            'Продажа кремации',
            'Сувениры',
            'Доп. услуги',
            'Онлайн-консультации',
            'Доставка урн',
            'Аренда',
            'Зарплата',
            'Коммунальные платежи',
            'Реклама',
            'Закупка материалов',
        ];

        foreach ($articles as $name) {
            Article::firstOrCreate(['name' => $name]);
        }

        // Можно создать ещё случайные статьи через фабрику
        // Article::factory()->count(0)->create();
    }
}

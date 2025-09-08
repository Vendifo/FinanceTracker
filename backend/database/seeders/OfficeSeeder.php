<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Office;

class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        $offices = [
            'Центральный офис',
            'Северный офис',
            'Южный офис',
            'Восточный офис',
            'Западный офис',
        ];

        foreach ($offices as $name) {
            Office::firstOrCreate(['name' => $name]);
        }

        // Дополнительно можно создать ещё случайные офисы через фабрику
        // Office::factory()->count(0)->create(); // пока не нужно
    }
}

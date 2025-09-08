<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class Roleseeder extends Seeder
{
    public function run(): void
    {
        $roles = ['user', 'manager', 'accountant'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}

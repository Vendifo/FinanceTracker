<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAllPasswords extends Command
{
    protected $signature = 'users:reset-passwords';
    protected $description = 'Set passwords for existing users from the list, defaulting to "password" if not in the list';

    public function handle()
    {
        // Список пользователей и их паролей
        $passwords = [
            'amayboroda' => 'u-evFrR6g_',
            'nkytepova' => '4sTRH-zcFL',
            'vmayboroda' => '7bL_uWuJFM',
            'ashvigel' => 'j2mTs_gnLj',
            'umegerya' => 'j2mTs_gnLj',
            'dsidorov' => 'j2mTs_gnLj',
            'rbondarenko' => 'j2mTs_gnLj',
            'msidorov' => 'j2mTs_gnLj',
            'aborovitskiy' => 'j2mTs_gnLj',
            'atokarev' => 'j2mTs_gnLj',
            'nchuyko' => '5enzSsa-Vt',
            'atagalmik' => 'j2mTs_gnLj',
            'kglushenkova' => '-CxM9hCjt1',
            'mkim' => '-WDrpB1N8s',
            'abershanskaya' => 'sCzNh_G7JB',
            'spazinich' => 'n66vz-8Nzt',
            'aptahina' => 'pgV_zYH5BL',
            'emakagon' => 'Q-Bv6iFhVy',
            'ekuznetcova' => 'f9EHMjQF-b',
        ];

        // Получаем всех пользователей из базы
        $users = User::all();

        foreach ($users as $user) {
            if (isset($passwords[$user->name])) {
                $user->password = Hash::make($passwords[$user->name]);
                $this->info("Password updated for user: {$user->name}");
            } else {
                $user->password = Hash::make('password');
                $this->warn("User {$user->name} not in list — set default password 'password'");
            }
            $user->save();
        }

        $this->info('All existing user passwords processed successfully.');
        return 0;
    }
}

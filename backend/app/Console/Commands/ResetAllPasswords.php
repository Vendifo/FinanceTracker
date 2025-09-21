<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAllPasswords extends Command
{
    protected $signature = 'users:reset-passwords';
    protected $description = 'Reset all user passwords to a standard password';

    public function handle()
    {
        $newPassword = 'password'; // стандартный пароль для всех
        $users = User::all();

        foreach ($users as $user) {
            $user->password = Hash::make($newPassword);
            $user->save();
        }

        $this->info('All user passwords have been reset to: ' . $newPassword);
        return 0;
    }
}

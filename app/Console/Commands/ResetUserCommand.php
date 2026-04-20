<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetUserCommand extends Command
{
    // Ini nama yang akan diketik di terminal
    protected $signature = 'user:reset-dummy';
    protected $description = 'Hapus semua user (kecuali admin) dan buat 1000 user baru';

    public function handle()
    {
        $this->warn("Menghapus user lama...");
        // Hapus semua user kecuali yang punya role admin
        User::where('role', '!=', 'admin')->delete();

        $this->info("Membuat 1000 user baru...");
        for ($i = 1; $i <= 100; $i++) {
            User::create([
                'name' => fake()->name(),
                'user_name' => fake()->userName() . $i, // Biar pasti unik
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]);
        }

        $this->info("Selesai! Database user kamu sekarang segar kembali.");
    }
}
/*php artisan user:reset-dummy*/

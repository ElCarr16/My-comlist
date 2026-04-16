<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat Akun Admin
        User::create([
            'user_name' => 'admin_utama',
            'name' => 'Administrator',
            'email' => 'admin@mycomlist.com',
            'password' => Hash::make('password123'), // Password default
            'role' => 'admin',
        ]);

        // 2. Membuat Akun User Biasa
        User::create([
            'user_name' => 'fajar_user',
            'name' => 'Fajar',
            'email' => 'user@mycomlist.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}

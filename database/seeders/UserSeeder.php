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
    
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'user_name' => fake()->userName(), // Tambahkan baris ini
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}

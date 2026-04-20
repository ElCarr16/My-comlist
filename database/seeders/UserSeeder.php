<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        //updateOrCreate agar tidak error jika data sudah ada
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL')],
            [
                'user_name' => 'admin_utama',
                'name' => 'Administrator',
                'password' => Hash::make(env('ADMIN_PASSWORD')),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@mycomlist.com'],
            [
                'user_name' => 'fajar_user',
                'name' => 'Fajar',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );
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

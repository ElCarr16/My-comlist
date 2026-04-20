<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AddUserCommand extends Command
{
    protected $signature = 'user:add-dummy';
    protected $description = 'Tambah 500 user baru tanpa menghapus yang sudah ada';

    public function handle()
    {
        $this->info("Menambah 500 user baru beserta aktivitasnya...");

        $comics = \App\Models\Comic::all();

        if ($comics->isEmpty()) {
            $this->error("Tidak ada data komik! Jalankan fetch:manga dulu.");
            return;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($comics) {
            for ($i = 1; $i <= 500; $i++) {
                // 1. Buat User
                $user = \App\Models\User::create([
                    'name' => fake()->name(),
                    'user_name' => fake()->userName() . rand(1, 99999),
                    'email' => fake()->unique()->safeEmail(),
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'role' => 'user',
                ]);

                // 2. Pilih 5 komik acak untuk diberi interaksi
                $randomComics = $comics->random(min(50, $comics->count()));

                foreach ($randomComics as $comic) {
                    // Beri Like secara acak
                    if (rand(0, 1)) {
                        \Illuminate\Support\Facades\DB::table('comic_likes')->insert([
                            'user_id' => $user->id,
                            'comic_id' => $comic->id,
                            'created_at' => now(),
                        ]);
                    }

                    // Beri Rating & Status secara acak
                    \Illuminate\Support\Facades\DB::table('comic_user')->insert([
                        'user_id' => $user->id,
                        'comic_id' => $comic->id,
                        'reading_status' => ['reading', 'completed', 'dropped'][rand(0, 2)],
                        'last_read_chapter' => rand(1, 50),
                        'score' => rand(6, 10), // Skor antara 6 sampai 10
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        $this->info("Berhasil! 500 user baru dan ribuan interaksi telah ditambahkan.");
    }
}
/*php artisan user:add-dummy*/

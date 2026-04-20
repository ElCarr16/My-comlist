<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan log database agar tidak memenuhi RAM saat proses 1000 data
        DB::disableQueryLog();

        $users = User::factory()->count(100)->create();
        $comics = Comic::all();

        if ($comics->isEmpty()) {
            $this->command->error("Tabel komik kosong! Jalankan fetch manga dulu.");
            return;
        }

        $this->command->info("Memproses 100 user dengan aktivitas acak...");

        // Gunakan Transaction agar prosesnya sangat cepat (hitungan detik)
        DB::transaction(function () use ($users, $comics) {
            foreach ($users as $user) {
                // Ambil 3-8 komik acak per user untuk diberi aktivitas
                $randomComics = $comics->random(rand(3, 50));

                foreach ($randomComics as $comic) {
                    // 1. Simpan Like (Jika angka acak 1, maka user me-like)
                    if (rand(0, 1)) {
                        DB::table('comic_likes')->insert([
                            'user_id' => $user->id,
                            'comic_id' => $comic->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    // 2. Simpan Rating/Progress
                    DB::table('comic_user')->insert([
                        'user_id' => $user->id,
                        'comic_id' => $comic->id,
                        'reading_status' => ['reading', 'completed', 'dropped'][rand(0, 2)],
                        'last_read_chapter' => rand(1, 50),
                        'score' => rand(5, 10),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        });

        $this->command->info("Seeder Review 1000 User berhasil!");
    }
}

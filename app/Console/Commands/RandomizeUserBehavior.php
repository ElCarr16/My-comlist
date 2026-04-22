<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Comic;
use Illuminate\Support\Facades\DB;

class RandomizeUserBehavior extends Command
{
    // Nama command yang akan dijalankan di terminal
    protected $signature = 'user:randomize-behavior';
    protected $description = 'Mengacak ulang interaksi (like, rating, baca) user dummy tanpa mengubah akunnya';

    public function handle()
    {
        $this->info("Memulai proses acak perilaku user...");

        // Ambil HANYA user yang memiliki email dengan akhiran @example.com / .net / .org
        $dummyUsers = User::where('email', 'like', '%@example.%')->get();

        $comics = Comic::all();
        $comics = Comic::all();

        if ($dummyUsers->isEmpty() || $comics->isEmpty()) {
            $this->error("Pastikan data user dummy dan komik sudah ada di database!");
            return;
        }

        $this->warn("Ditemukan {$dummyUsers->count()} user dummy. Memperbarui interaksi mereka...");

        DB::transaction(function () use ($dummyUsers, $comics) {
            // bersihkan dulu riwayat dummy.
            $dummyUserIds = $dummyUsers->pluck('id');
            DB::table('comic_likes')->whereIn('user_id', $dummyUserIds)->delete();
            DB::table('comic_user')->whereIn('user_id', $dummyUserIds)->delete();

            // 3. MULAI ACAK PERILAKU BARU
            $bar = $this->output->createProgressBar(count($dummyUsers));
            $bar->start();

            foreach ($dummyUsers as $user) {
                // Random jumlah komik yang akan diinteraksi (0 sampai 15 komik)
                $jumlahInteraksi = rand(0, 100);
                if ($jumlahInteraksi == 0) {
                    $bar->advance();
                    continue; // User ini sedang malas, lewati
                }

                $randomComics = $comics->random(min($jumlahInteraksi, $comics->count()));

                foreach ($randomComics as $comic) {
                    // Aksi Like (peluang 40%)
                    if (rand(1, 100) <= 40) {
                        DB::table('comic_likes')->insert([
                            'user_id' => $user->id,
                            'comic_id' => $comic->id,
                            'created_at' => now(),
                        ]);
                    }

                    // Aksi Baca & Rating (peluang 80%)
                    if (rand(1, 100) <= 80) {
                        $status = ['reading', 'completed', 'dropped', 'plan_to_read'][array_rand(['reading', 'completed', 'dropped', 'plan_to_read'])];
                        $score = (rand(1, 100) <= 70) ? rand(5, 10) : null;

                        $maxChapter = $comic->total_chapter > 0 ? $comic->total_chapter : 50;
                        $lastRead = ($status == 'completed') ? $maxChapter : rand(0, $maxChapter);

                        DB::table('comic_user')->insert([
                            'user_id' => $user->id,
                            'comic_id' => $comic->id,
                            'reading_status' => $status,
                            'last_read_chapter' => $lastRead,
                            'score' => $score,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                $bar->advance();
            }
            $bar->finish();
        });

        $this->newLine(2);
        $this->info("Selesai! Perilaku user dummy berhasil diacak ulang secara realistis.");
    }
}

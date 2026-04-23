<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TrackedComic;
use App\Services\ComicScraper;

class ScrapeComics extends Command
{
    protected $signature = 'scrape:comics';
    protected $description = 'Auto-sync massal semua akun MyAnimeList';

    public function handle()
    {
        $sources = TrackedComic::all();

        if ($sources->isEmpty()) {
            $this->warn("Tidak ada akun MAL yang dilacak.");
            return;
        }

        foreach ($sources as $source) {
            $username = str_replace(' (MAL Account)', '', $source->title);
            $this->info("Sedang sinkronisasi akun: {$username}...");

            $hasil = ComicScraper::syncUserList($username, $source->user_id);

            // Jika hasil kembaliannya berupa teks yang diawali 'ERROR'
            if (is_string($hasil) && str_starts_with($hasil, 'ERROR')) {
                $this->error("Gagal sinkronisasi untuk: {$username}");
                $this->error("Alasan: " . $hasil); // Tampilkan alasan aslinya!
                continue;
            }

            $source->update([
                'last_chapter_title' => 'Terakhir Auto-Sync: ' . now()->format('d M Y, H:i')
            ]);

            $this->info("Berhasil! {$hasil} komik diperbarui.");
            sleep(2);
        }
        $this->info("Proses selesai.");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Comic;
use App\Models\Genre;

class FetchJikanManga extends Command
{
    // Bisa ambil 1 halaman: php artisan fetch:manga 7
    // Bisa ambil rentang: php artisan fetch:manga 1 5
    protected $signature = 'fetch:manga {start=1} {end?} {--type=}';

    protected $description = 'Mengumpulkan data Manga dari Jikan API berdasarkan jumlah halaman (1 Page = 25 Manga)';

    public function handle()
    {
        $startPage = $this->argument('start');
        $endPage = $this->argument('end') ?? $startPage;

        // 1. AMBIL OPSI TIPE DARI TERMINAL
        $type = $this->option('type');
        $totalSaved = 0;

        // Bikin pesan info yang lebih dinamis
        $tipeInfo = $type ? strtoupper($type) : 'SEMUA TIPE';
        $this->info("Menarik data $tipeInfo dari halaman $startPage sampai $endPage...");

        for ($page = $startPage; $page <= $endPage; $page++) {
            $this->info("--- Mengambil Data Halaman $page ---");

            try {
                // 2. MODIFIKASI URL UNTUK MENERIMA TIPE
                $url = "https://api.jikan.moe/v4/top/manga?page={$page}";

                // Jika user memasukkan --type, tambahkan parameter ke URL
                if ($type) {
                    $url .= "&type={$type}";
                }

                $response = Http::timeout(60)->get($url);

                if ($response->successful()) {
                    $mangas = $response->json()['data'];

                    foreach ($mangas as $manga) {

                        // --- PROSES EKSTRAK JUDUL ALTERNATIF ---
                        $altTitles = [];
                        if (isset($manga['titles'])) {
                            foreach ($manga['titles'] as $t) {
                                if ($t['type'] !== 'Default') {
                                    $altTitles[] = $t['title'];
                                }
                            }
                        }
                        $altTitlesString = implode(', ', $altTitles);
                        // ---------------------------------------

                        $comic = Comic::updateOrCreate(
                            ['mal_id' => $manga['mal_id']],
                            [
                                'title'              => $manga['title'],
                                'alternative_titles' => $altTitlesString, // SIMPAN DI SINI
                                'slug'               => Str::slug($manga['title']) . '-' . rand(100, 999),
                                'synopsis'           => $manga['synopsis'] ?? 'Belum ada sinopsis.',
                                'author'             => isset($manga['authors'][0]['name']) ? $manga['authors'][0]['name'] : 'Unknown Author',
                                'type'               => $manga['type'] ?? 'Manga',
                                'release_year'       => isset($manga['published']['prop']['from']['year']) ? intval($manga['published']['prop']['from']['year']) : null,
                                'finish_year'        => isset($manga['published']['prop']['to']['year']) ? intval($manga['published']['prop']['to']['year']) : null,
                                'cover_image'        => $manga['images']['webp']['image_url'] ?? null,
                                'status'             => $manga['status'] ?? 'Unknown',
                                'total_chapter'      => $manga['chapters'] ?? 0,
                                'total_volume'       => $manga['volumes'] ?? 0,
                                'mal_score'          => $manga['score'] ?? null,
                                'mal_favorites'      => $manga['favorites'] ?? 0,
                            ]
                        );

                        $genreIds = [];
                        if (isset($manga['genres'])) {
                            foreach ($manga['genres'] as $apiGenre) {
                                $genre = Genre::firstOrCreate(['name' => $apiGenre['name']]);
                                $genreIds[] = $genre->id;
                            }
                        }
                        $comic->genres()->sync($genreIds);

                        if ($comic->id) {
                            $this->line("- Disimpan (ID DB: {$comic->id}): " . $manga['title']);
                            $totalSaved++;
                        }
                    }

                    if ($page < $endPage) {
                        $this->warn("Menunggu 2 detik sebelum pindah ke halaman berikutnya...");
                        sleep(2);
                    }
                } else {
                    $this->error("Gagal terhubung ke Jikan API. Status: " . $response->status());
                    break;
                }
            } catch (\Exception $e) {
                $this->error("Gagal di halaman $page: " . $e->getMessage());
            }
        }

        $this->info("\nSELESAI! Sebanyak $totalSaved komik berhasil ditambahkan/diupdate.");
    }
}
/*
command
 php artisan fetch:manga (number)
 php artisan fetch:manga 1 5
 php artisan fetch:manga 1 2 --type=manhwa manga manhua
*/

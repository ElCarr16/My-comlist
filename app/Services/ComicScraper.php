<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Models\Comic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComicScraper
{
    public static function syncUserList($username, $userId)
    {
        $client = new Client([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]
        ]);

        try {
            $response = $client->get("https://myanimelist.net/mangalist/{$username}/load.json");
            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['errors'])) return "ERROR: " . $data['errors'][0]['message'];
            if (!is_array($data)) return "ERROR: Tidak dapat membaca daftar komik. Pastikan Publik.";

            $komikBerhasil = 0;

            foreach ($data as $item) {
                $mangaTitle = $item['manga_title'];

                // TAHAP 1: Masukkan komik ke KATALOG UTAMA (tabel 'comics') jika belum ada
                $comic = Comic::firstOrCreate(
                    ['title' => $mangaTitle], // Cari berdasarkan judul
                    [
                        'slug'          => Str::slug($mangaTitle),
                        'cover_image'   => $item['manga_image_path'] ?? null,
                        'total_chapter' => $item['manga_num_chapters'] ?? 0,
                        'mal_id'        => $item['manga_id'] ?? null,
                    ]
                );

                // Mapping Status sesuai format text di database kamu
                $statusMap = [
                    1 => 'reading',
                    2 => 'completed',
                    3 => 'dropped', // Di MAL ini On-Hold, kita samakan ke dropped/plan_to_read
                    4 => 'dropped',
                    6 => 'plan_to_read'
                ];
                $readingStatus = $statusMap[$item['status']] ?? 'plan_to_read';

                // TAHAP 2: Masukkan Progress Bacaan User ke tabel 'comic_user'
                DB::table('comic_user')->updateOrInsert(
                    [
                        'user_id'  => $userId,
                        'comic_id' => $comic->id, // Mengambil ID dari komik di tahap 1
                    ],
                    [
                        'reading_status'    => $readingStatus,
                        'score'             => $item['score'] == 0 ? null : $item['score'],
                        'last_read_chapter' => $item['num_read_chapters'] ?? 0,
                        'updated_at'        => now(),
                        // created_at tidak perlu ditulis karena updateOrInsert otomatis menanganinya jika pakai Eloquent,
                        // tapi karena pakai DB facade, kita biarkan default timestamp DB yang bekerja.
                    ]
                );

                $komikBerhasil++;
            }
            return $komikBerhasil;
        } catch (\Exception $e) {
            return "ERROR: " . $e->getMessage();
        }
    }
}

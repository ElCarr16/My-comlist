<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackerController extends Controller
{
    public function update(Request $request, $comicId)
    {
        // 1. Validasi inputan (Termasuk menangkap skor 1-10)
        $request->validate([
            'reading_status'    => 'required|in:plan_to_read,reading,completed,dropped',
            'last_read_chapter' => 'required|integer|min:0',
            'score'             => 'nullable|integer|min:1|max:10' // Ini wajib ada agar sistem tahu ada skor
        ]);

        $user = Auth::user();

        // 2. Logika Cerdas: Cek apakah user sudah pernah menambahkan komik ini sebelumnya
        if ($user->trackedComics()->where('comics.id', $comicId)->exists()) {
            // Jika SUDAH ADA di My List, kita UPDATE nilainya (termasuk skor terbarunya)
            $user->trackedComics()->updateExistingPivot($comicId, [
                'reading_status'    => $request->reading_status,
                'last_read_chapter' => $request->last_read_chapter,
                'score'             => $request->score // Simpan skor ke database
            ]);
        } else {
            // Jika BELUM ADA, kita TAMBAHKAN data baru
            $user->trackedComics()->attach($comicId, [
                'reading_status'    => $request->reading_status,
                'last_read_chapter' => $request->last_read_chapter,
                'score'             => $request->score // Simpan skor ke database
            ]);
        }

        return back()->with('success', 'Berhasil! My List dan Skor kamu sudah tersimpan.');
    }
}

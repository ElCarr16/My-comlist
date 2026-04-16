<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackerController extends Controller
{
    public function update(Request $request, Comic $comic)
    {
        // Validasi input dari user
        $request->validate([
            'reading_status' => 'required|in:reading,completed,plan_to_read,dropped',
            'score' => 'nullable|integer|min:1|max:10',
            // Pastikan chapter yang dibaca tidak melebihi total chapter komik
            'last_read_chapter' => 'required|integer|min:0|max:' . $comic->total_chapter,
        ]);

        // Simpan atau Update ke tabel pivot comic_user
        // syncWithoutDetaching sangat penting di sini!
        // Ini memastikan komik lain di My List tidak ikut terhapus.
        auth()->user()->trackedComics()->syncWithoutDetaching([
            $comic->id => [
                'reading_status' => $request->reading_status,
                'score' => $request->score,
                'last_read_chapter' => $request->last_read_chapter,
            ]
        ]);

        return back()->with('success', 'Progress bacaan berhasil diupdate di My List!');
    }
}

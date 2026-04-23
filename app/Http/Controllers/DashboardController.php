<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil data tracker dan like milik user ini
        // Menggunakan eager loading (with) komiknya agar tidak lemot
        $user->load(['trackedComics', 'likedComics']);

        $trackedComics = $user->trackedComics;

        // Hitung Statistik Keren untuk Profil
        $stats = [
            'total_tracked' => $trackedComics->count(),
            'reading'       => $trackedComics->where('pivot.reading_status', 'reading')->count(),
            'completed'     => $trackedComics->where('pivot.reading_status', 'completed')->count(),
            'plan_to_read'  => $trackedComics->where('pivot.reading_status', 'plan_to_read')->count(),
            'dropped'       => $trackedComics->where('pivot.reading_status', 'dropped')->count(),
            'avg_score'     => round($trackedComics->avg('pivot.score'), 1) ?? 0,
            'total_liked'   => $user->likedComics->count(),
        ];

        return view('user.dashboard', compact('user', 'trackedComics', 'stats'));
    }

    // FUNGSI BARU: Hapus histori komik dari dashboard
    public function removeComicFromHistory($id)
    {
        // Menghapus data komik spesifik dari tabel pivot (comic_user) milik user yang sedang login
        auth()->user()->comics()->detach($id);

        return back()->with('success', 'Komik berhasil dihapus dari histori bacaan.');
    }
}
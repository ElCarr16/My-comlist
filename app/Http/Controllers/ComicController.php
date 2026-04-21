<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComicController extends Controller
{
    public function index(Request $request)
    {
        // 1. Menghitung Like manual dan Rating dari tracker
        $query = Comic::with('genres')
            ->withCount('likedByUsers')
            ->withAvg('users', 'comic_user.score');

        // 2. Filter Pencarian Judul, Sinopsis, dan Penulis (Versi Controller Biasa)

        // Cek apakah user mengetik sesuatu di URL (?search=...)
        if ($request->filled('search')) {

            // Ambil kata kunci dari $request, ubah ke huruf kecil, dan tambah %
            $searchTerm = '%' . strtolower($request->search) . '%';

            // Bungkus dalam function($q) agar tidak merusak filter Genre/Tipe
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(synopsis) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(author) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(alternative_titles) LIKE ?', [$searchTerm]);
            });
        }

        // 3. Filter Kategori Genre
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $genre = $request->genre;
                if (is_array($genre)) {
                    $q->whereIn('genres.id', $genre);
                } else {
                    $q->where('genres.id', $genre);
                }
            });
        }

        // 4. Filter Tahun Rilis
        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
        }

        // 5. Fitur Pengurutan (Sorting)
        $sort = $request->input('sort', 'latest');

        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'popular':
                $query->orderBy('liked_by_users_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('users_avg_comic_user_score', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $comics = $query->paginate(12)->withQueryString();
        $genres = Genre::orderBy('name', 'asc')->get();

        return view('comics.index', compact('comics', 'genres'));
    }

    public function show(Comic $comic)
    {
        // Gunakan 'load' untuk relasi, 'loadAvg' untuk rata-rata, dan 'loadCount' untuk jumlah
        $comic->load(['genres']);
        $comic->loadAvg('users as avg_score', 'comic_user.score');
        $comic->loadCount('likedByUsers');

        return view('comics.show', compact('comic'));
    }

    // 6. Fungsi Like dengan AJAX (JSON) dan Auth Facade
    public function toggleLike(Comic $comic)
    {
        // Menggunakan Auth::check() agar VS Code tidak error
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Toggle: Jika belum Like jadi Like, jika sudah Like jadi Unlike
        Auth::user()->likedComics()->toggle($comic->id);

        // Ambil data terbaru untuk dikirim ke JavaScript di tampilan depan
        $isLiked = Auth::user()->likedComics()->where('comics.id', $comic->id)->exists();
        $likesCount = $comic->likedByUsers()->count();

        // Mengembalikan data JSON agar halaman tidak reload (Mulus!)
        return response()->json([
            'isLiked' => $isLiked,
            'likesCount' => $likesCount
        ]);
    }
}

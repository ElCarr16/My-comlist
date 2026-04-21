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
        // 1. Inisialisasi Query
        $query = Comic::query()
            ->with('genres')
            ->withCount('likedByUsers')
            ->withAvg('users as local_avg_score', 'comic_user.score');

        // 2. Filter Pencarian
        if ($request->filled('search')) {
            $searchTerm = '%' . strtolower($request->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(synopsis) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(author) LIKE ?', [$searchTerm])
                    ->orWhereRaw('LOWER(alternative_titles) LIKE ?', [$searchTerm]);
            });
        }

        // 3. Filter Genre
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $genre = $request->genre;
                is_array($genre) ? $q->whereIn('genres.id', $genre) : $q->where('genres.id', $genre);
            });
        }

        // 4. Filter Tahun
        if ($request->filled('year')) {
            $query->where('release_year', $request->year);
        }

        // 5. Fitur Pengurutan Gabungan (Rating & Popularitas)
        $sort = $request->input('sort', 'latest');

        switch ($sort) {
            case 'popular':
                // (MAL Favorites + Lokal Likes)
                $query->orderByRaw('(COALESCE(mal_favorites, 0) + liked_by_users_count) DESC');
                break;
            case 'rating':
                $query->orderByRaw("(COALESCE((SELECT AVG(score) FROM comic_user WHERE comic_id = comics.id), 0) + COALESCE(mal_score, 0)) DESC");
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
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
        $comic->load(['genres']);
        $comic->loadAvg('users as avg_score', 'comic_user.score');
        $comic->loadCount('likedByUsers');

        return view('comics.show', compact('comic'));
    }

    public function toggleLike(Comic $comic)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        Auth::user()->likedComics()->toggle($comic->id);

        $isLiked = Auth::user()->likedComics()->where('comics.id', $comic->id)->exists();
        $likesCount = $comic->likedByUsers()->count();

        return response()->json([
            'isLiked' => $isLiked,
            'likesCount' => $likesCount
        ]);
    }
}

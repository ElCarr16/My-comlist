<?php

namespace App\Http\Controllers\Admin; // Hanya ada satu namespace, ini yang benar!

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class ComicController extends Controller
{
    public function index()
    {
        $comics = Comic::with('genres')->latest()->get();
        return view('admin.comics.index', compact('comics'));
    }

    public function create()
    {
        $genres = Genre::orderBy('name')->get();
        return view('admin.comics.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255|unique:comics,title',
            'synopsis'      => 'nullable|string',
            'author'        => 'nullable|string|max:255',
            'status' => 'required|in:on-going,completed,dropped,dikapak,hiatus',
            'total_chapter' => 'required|integer|min:0',
            'total_volume'  => 'nullable|integer|min:0', // Tambahan Volume
            'release_year'  => 'nullable|integer|min:1900|max:2100', // Tambahan Tahun
            'finish_year'   => 'nullable|integer|min:1900|max:2100', // Tambahan Tahun
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:2048',
            'type'          => 'required|in:manga,manhwa,manhua,oneshot',
            'genres'        => 'required|array',
            'genres.*'      => 'exists:genres,id'
        ]);

        $data = $request->except(['cover_image', 'genres']);
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $comic = Comic::create($data);
        $comic->genres()->sync($request->genres);

        return redirect()->route('admin.comics.index')->with('success', 'Komik berhasil ditambahkan!');
    }

    public function edit(Comic $comic)
    {
        $genres = Genre::orderBy('name')->get();
        $comic->load('genres');
        return view('admin.comics.edit', compact('comic', 'genres'));
    }

    public function update(Request $request, Comic $comic)
    {
        $request->validate([
            'title'         => 'required|string|max:255|unique:comics,title,' . $comic->id,
            'synopsis'      => 'nullable|string',
            'author'        => 'nullable|string|max:255',
            'status'        => 'required|in:on-going,completed,dropped,dikapak', // Update status baru
            'total_chapter' => 'required|integer|min:0',
            'total_volume'  => 'nullable|integer|min:0', // Tambahan Volume
            'release_year'  => 'nullable|integer|min:1900|max:2100', // Tambahan Tahun
            'finish_year'   => 'nullable|integer|min:1900|max:2100', // Tambahan Tahun
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp,jfif|max:2048',
            'type'          => 'required|in:manga,manhwa,manhua,oneshot',
            'genres'        => 'required|array',
            'genres.*'      => 'exists:genres,id'
        ]);

        $data = $request->except(['cover_image', 'genres']);
        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('cover_image')) {
            if ($comic->cover_image && Storage::disk('public')->exists($comic->cover_image)) {
                Storage::disk('public')->delete($comic->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $comic->update($data);
        $comic->genres()->sync($request->genres);

        return redirect()->route('admin.comics.index')->with('success', 'Komik berhasil diperbarui!');
    }

    public function destroy(Comic $comic)
    {
        if ($comic->cover_image && Storage::disk('public')->exists($comic->cover_image)) {
            Storage::disk('public')->delete($comic->cover_image);
        }

        $comic->delete();

        return redirect()->route('admin.comics.index')->with('success', 'Komik berhasil dihapus!');
    }
    // Fungsi untuk memproses tombol Like tanpa Reload (AJAX)
    public function toggleLike(Comic $comic)
    {
        // 1. Ubah auth()->check() menjadi Auth::check()
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // 2. Ubah auth()->user() menjadi Auth::user()
        Auth::user()->likedComics()->toggle($comic->id);

        // Ambil data terbaru
        $isLiked = Auth::user()->likedComics->contains($comic->id);
        $likesCount = $comic->likedByUsers()->count();

        return response()->json([
            'isLiked' => $isLiked,
            'likesCount' => $likesCount
        ]);
    }
}

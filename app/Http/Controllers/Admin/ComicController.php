<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Untuk menghapus gambar lama

class ComicController extends Controller
{
    public function index()
    {
        // Gunakan with('genres') agar load data cepat (Mencegah N+1 Query)
        $comics = Comic::with('genres')->latest()->get();
        return view('admin.comics.index', compact('comics'));
    }

    public function create()
    {
        // Ambil semua genre untuk ditampilkan sebagai checkbox di form
        $genres = Genre::orderBy('name')->get();
        return view('admin.comics.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255|unique:comics,title',
            'synopsis'      => 'nullable|string',
            'author'        => 'nullable|string|max:255',
            'status'        => 'required|in:pre-release,on-going,stopped,completed',
            'total_chapter' => 'required|integer|min:0',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Maksimal 2MB
            'genres'        => 'required|array', // Pastikan minimal ada 1 genre yang dipilih
            'genres.*'      => 'exists:genres,id' // Pastikan ID genre-nya valid
        ]);

        $data = $request->except(['cover_image', 'genres']);
        $data['slug'] = Str::slug($request->title);

        // Proses Upload Gambar
        if ($request->hasFile('cover_image')) {
            // Simpan gambar ke folder storage/app/public/covers
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // 1. Simpan data komik ke database
        $comic = Comic::create($data);

        // 2. Simpan relasi ke tabel pivot comic_genre (Sihir Laravel!)
        $comic->genres()->sync($request->genres);

        return redirect()->route('admin.comics.index')->with('success', 'Komik berhasil ditambahkan!');
    }

    public function edit(Comic $comic)
    {
        $genres = Genre::orderBy('name')->get();
        // Load relasi genre yang sudah dimiliki komik ini untuk dicentang otomatis di form
        $comic->load('genres');
        return view('admin.comics.edit', compact('comic', 'genres'));
    }

    public function update(Request $request, Comic $comic)
    {
        $request->validate([
            'title'         => 'required|string|max:255|unique:comics,title,' . $comic->id,
            'synopsis'      => 'nullable|string',
            'author'        => 'nullable|string|max:255',
            'status'        => 'required|in:pre-release,on-going,stopped,completed',
            'total_chapter' => 'required|integer|min:0',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'genres'        => 'required|array',
            'genres.*'      => 'exists:genres,id'
        ]);

        $data = $request->except(['cover_image', 'genres']);
        $data['slug'] = Str::slug($request->title);

        // Proses Update Gambar
        if ($request->hasFile('cover_image')) {
            // Jika komik sudah punya gambar lama, hapus dulu agar storage tidak penuh
            if ($comic->cover_image && Storage::disk('public')->exists($comic->cover_image)) {
                Storage::disk('public')->delete($comic->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // 1. Update data inti komik
        $comic->update($data);

        // 2. Update relasi genre (sync otomatis menghapus yang tidak dicentang & menambah yang baru)
        $comic->genres()->sync($request->genres);

        return redirect()->route('admin.comics.index')->with('success', 'Komik berhasil diperbarui!');
    }

    public function destroy(Comic $comic)
    {
        // Hapus file gambar dari storage jika ada
        if ($comic->cover_image && Storage::disk('public')->exists($comic->cover_image)) {
            Storage::disk('public')->delete($comic->cover_image);
        }

        // Relasi di tabel comic_genre akan otomatis terhapus karena kita pakai onDelete('cascade') di migrasi
        $comic->delete();

        return redirect()->route('admin.comics.index')->with('success', 'Komik berhasil dihapus!');
    }
}

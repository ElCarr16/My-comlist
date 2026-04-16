<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Jangan lupa import ini untuk bikin slug otomatis

class GenreController extends Controller
{
    // 1. Menampilkan daftar semua genre
    public function index()
    {
        $genres = Genre::latest()->get();
        return view('admin.genres.index', compact('genres'));
    }

    // 2. Menampilkan form tambah genre baru
    public function create()
    {
        return view('admin.genres.create');
    }

    // 3. Memproses data dari form tambah ke database
    public function store(Request $request)
    {
        // Validasi: nama wajib diisi dan tidak boleh kembar
        $request->validate([
            'name' => 'required|string|max:255|unique:genres,name'
        ]);

        // Simpan ke database, slug dibuat otomatis dari nama
        Genre::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil ditambahkan!');
    }

    // 4. (Opsional) Menampilkan detail 1 genre. Biasanya untuk genre dilewati saja
    public function show(Genre $genre)
    {
        return view('admin.genres.show', compact('genre'));
    }

    // 5. Menampilkan form edit genre
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    // 6. Memproses perubahan data dari form edit
    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            // Validasi: tidak boleh kembar, KECUALI dengan namanya sendiri saat ini
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id
        ]);

        $genre->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil diperbarui!');
    }

    // 7. Menghapus genre dari database
    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre berhasil dihapus!');
    }
}

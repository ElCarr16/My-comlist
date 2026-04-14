<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;

class ComicController extends Controller
{
    public function index()
    {
        // Mengambil semua data komik sekaligus dengan relasi genrenya (Eager Loading)
        // Ini mencegah masalah performa N+1 query yang sering dicek saat evaluasi project
        $comics = Comic::with('genres')->latest()->get();

        // Mengirim data $comics ke file Blade
        return view('comics.index', compact('comics'));
    }

    public function show(Comic $comic)
    {
        // Load relasi genre untuk 1 komik spesifik
        $comic->load('genres');
        
        return view('comics.show', compact('comic'));
    }
}
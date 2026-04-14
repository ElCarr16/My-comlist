<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComicController;

Route::get('/', function () {
    return view('welcome');
});

// Route Publik untuk melihat daftar komik
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{comic:slug}', [ComicController::class, 'show'])->name('comics.show');

// Route khusus User yang sudah Login (Untuk fitur Tracker)
Route::middleware('auth')->group(function () {
    // Misalnya nanti kita buat fitur tambah komik ke tracker
    // Route::post('/tracker/add', [TrackerController::class, 'store'])->name('tracker.add');
});
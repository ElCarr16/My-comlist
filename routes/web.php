<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\TrackerController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComicController as AdminComicController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

// Route Publik untuk melihat daftar komik
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{comic:slug}', [ComicController::class, 'show'])->name('comics.show');


// Route untuk pengunjung yang belum login (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
});

// Route Logout (Hanya bisa diakses kalau sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute khusus Admin (Dilindungi Auth & IsAdmin)
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    // Halaman Utama Dashboard Admin (/admin)
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Otomatis membuat semua rute CRUD
    Route::resource('users', UserController::class);
    Route::resource('comics', AdminComicController::class);
    Route::resource('genres', GenreController::class);
});
// Route khusus User yang sudah Login (Untuk fitur Tracker)
Route::middleware('auth')->group(function () {
    // Halaman Dashboard User
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    // Route untuk update My List
    Route::post('/tracker/{comic}', [TrackerController::class, 'update'])->name('tracker.update');
    // Misalnya nanti kita buat fitur tambah komik ke tracker
    // Route::post('/tracker/add', [TrackerController::class, 'store'])->name('tracker.add');

});

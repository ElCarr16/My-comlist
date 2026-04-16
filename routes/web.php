<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\TrackerController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComicController as AdminComicController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Route Publik untuk melihat daftar komik
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{comic:slug}', [ComicController::class, 'show'])->name('comics.show');

// Route untuk pengunjung yang BELUM login (Guest)
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
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('comics', AdminComicController::class);
    Route::resource('genres', GenreController::class);

    // Rute profil dihapus dari sini karena ini area Admin
});

// Route khusus User yang SUDAH Login (Auth)
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    // --- MULAI AREA PROFIL ---
    // 1. Halaman Lihat Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    // 2. Halaman Form Edit Profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    // 3. Proses Simpan Edit Profil
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
    // --- AKHIR AREA PROFIL ---

    Route::post('/tracker/{comic}', [TrackerController::class, 'update'])->name('tracker.update');
    Route::post('/comics/{comic}/like', [ComicController::class, 'toggleLike'])->name('comics.like');
});

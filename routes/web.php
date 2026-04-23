<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Guest & Auth Controllers
use App\Http\Controllers\AuthController;

// Public & User Controllers
use App\Http\Controllers\ComicController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Admin Controllers
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComicController as AdminComicController;
use App\Http\Controllers\Admin\GenreController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ComicController::class)->group(function () {
    Route::get('/comics', 'index')->name('comics.index');
    Route::get('/comics/{comic:slug}', 'show')->name('comics.show');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'processRegister')->name('register.process');

    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'processLogin')->name('login.process');

    Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('/forgot-password', 'sendOtp')->name('forgot.send-otp');
    Route::get('/forgot-password/otp/{email}', 'showOtpInput')->name('forgot.otp-input');
    Route::post('/forgot-password/otp', 'verifyOtp')->name('forgot.verify-otp');
    Route::get('/reset-password', 'showResetPassword')->name('forgot.process-reset');
    Route::post('/reset-password', 'processResetPassword')->name('forgot.reset-password.process');
});

/*
|--------------------------------------------------------------------------
| User Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & Koleksi
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('user.dashboard');

        // Rute Hapus Histori (Sudah diarahkan ke DashboardController!)
        Route::delete('/koleksi/hapus/{id}', 'removeComicFromHistory')->name('user.comic.remove');
    });

    // Profil & Integrasi (Menggunakan Prefix /profile agar rapi)
    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        // Pengaturan Profil
        Route::get('/', 'index')->name('user.profile');
        Route::get('/edit', 'edit')->name('user.profile.edit');
        Route::post('/update', 'update')->name('user.profile.update');
        Route::post('/password', 'updatePassword')->name('user.password.update');

        // Source URL Manual
        Route::post('/source', 'storeSource')->name('user.source.store');
        Route::put('/source/{id}', 'updateSource')->name('user.source.update');
        Route::delete('/source/{id}', 'deleteSource')->name('user.source.delete');

        // Integrasi MyAnimeList
        Route::post('/mal/store', 'storeMalAccount')->name('user.source.storeMal');
        Route::put('/mal/update/{id}', 'updateMalAccount')->name('user.source.updateMal');
        Route::post('/mal/sync/{id}', 'executeMalSync')->name('user.sync.mal.execute');
    });

    // Interaksi Komik (Like & Tracker)
    Route::post('/comics/{comic}/like', [ComicController::class, 'toggleLike'])->name('comics.like');
    Route::post('/tracker/{comic}', [TrackerController::class, 'update'])->name('tracker.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('comics', AdminComicController::class);
    Route::resource('genres', GenreController::class);
});

/*
|--------------------------------------------------------------------------
| Storage / File Viewer Routes
|--------------------------------------------------------------------------
*/
Route::get('/storage/profiles/{filename}', function ($filename) {
    if (!Storage::exists('profiles/' . $filename)) {
        abort(404);
    }
    return Storage::response('profiles/' . $filename);
})->name('profile.image.view');

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil dengan statistik koleksi.
     */
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'reading' => $user->comics()->where('reading_status', 'reading')->count(),
            'finished' => $user->comics()->where('reading_status', 'finished')->count(),
            'total_chapter' => $user->comics()->sum('last_read_chapter'),
        ];

        return view('user.profile', compact('user', 'stats'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.edit-profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|alpha_dash|max:20|unique:users,user_name,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'user_name.unique' => 'Username ini sudah dipakai orang lain.',
            'user_name.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).'
        ]);

        if ($request->input('remove_image') == '1') {
            if ($user->profile_image) {
                Storage::delete($user->profile_image);
                $user->profile_image = null;
            }
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profiles');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->user_name = $request->user_name;
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }
    public function storeSource(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required|url'
        ]);

        // Tambahkan 'user_id' di sini menggunakan auth()->id()
        \App\Models\TrackedComic::create([
            'user_id' => auth()->id(),
            'title'   => $request->title,
            'url'     => $request->url,
        ]);

        return back()->with('success', 'URL berhasil ditambahkan.');
    }

    public function updateSource(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required|url'
        ]);

        \App\Models\TrackedComic::where('user_id', auth()->id())->where('id', $id)->update([
            'title'   => $request->title,
            'url'     => $request->url,
        ]);

        return back()->with('success', 'URL berhasil diperbarui.');
    }

    public function deleteSource($id)
    {
        \App\Models\TrackedComic::where('user_id', auth()->id())->where('id', $id)->delete();
        return back()->with('success', 'URL berhasil dihapus.');
    }

    // 1. Fungsi khusus untuk MENYIMPAN akun ke database (dipanggil dari Modal)
    public function storeMalAccount(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'url' => 'required|url',
        ]);

        \App\Models\TrackedComic::create([
            'user_id' => auth()->id(),
            // Kita simpan dengan format jelas agar mudah diekstrak nanti
            'title' => $request->username . ' (MAL Account)',
            'url' => $request->url,
            'last_chapter_title' => 'Menunggu Sinkronisasi...'
        ]);

        return back()->with('success', 'Akun MyAnimeList berhasil ditambahkan!');
    }

    public function executeMalSync($id)
    {
        $source = \App\Models\TrackedComic::findOrFail($id);
        $username = str_replace(' (MAL Account)', '', $source->title);

        // Panggil fungsi ComicScraper yang sudah terbukti berhasil di terminal!
        $hasil = \App\Services\ComicScraper::syncUserList($username, auth()->id());

        // Cek jika kembaliannya berupa pesan Error
        if (is_string($hasil) && \Illuminate\Support\Str::startsWith($hasil, 'ERROR')) {
            return back()->with('error', 'Gagal Sync! ' . $hasil);
        }

        // Jika berhasil
        $source->update([
            'last_chapter_title' => 'Terakhir Sync: ' . now()->format('d M Y, H:i')
        ]);

        return back()->with('success', "Berhasil sinkronisasi {$hasil} komik dari MAL!");
    }

    // FITUR BARU: Fungsi untuk Update/Edit Akun MAL
    public function updateMalAccount(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string',
            'url' => 'required|url',
        ]);

        \App\Models\TrackedComic::where('user_id', auth()->id())->where('id', $id)->update([
            'title' => $request->username . ' (MAL Account)',
            'url'   => $request->url,
            'last_chapter_title' => 'Menunggu Sinkronisasi (Diedit)...'
        ]);

        return back()->with('success', 'Data Akun MyAnimeList berhasil diperbarui!');
    }
}

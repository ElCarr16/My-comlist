<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil dengan statistik koleksi.
     */
    public function index()
    {
        $user = Auth::user();

        // Menyiapkan data statistik berdasarkan relasi di model User
        // Pastikan relasi 'comics' sudah didefinisikan di Model User
        $stats = [
            'reading' => $user->comics()->where('reading_status', 'reading')->count(),
            'finished' => $user->comics()->where('reading_status', 'finished')->count(),
            'total_chapter' => $user->comics()->sum('last_read_chapter'),
        ];

        return view('user.profile', compact('user', 'stats'));
    }

    /**
     * Menampilkan form edit profil.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('user.edit-profile', compact('user'));
    }

    /**
     * Memproses pembaruan data profil dan penghapusan foto.
     */
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

        // 1. Logika Hapus Foto (Jika user menekan tombol hapus)
        if ($request->input('remove_image') == '1') {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
                $user->profile_image = null;
            }
        }

        // 2. Logika Upload Foto Baru (Jika user memilih file baru)
        if ($request->hasFile('profile_image')) {
            // Hapus foto lama jika ada sebelum upload yang baru
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Simpan foto ke folder 'profiles' di disk 'public'
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        // 3. Simpan data user
        $user->name = $request->name;
        $user->user_name = $request->user_name;
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}

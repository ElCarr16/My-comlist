<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Fungsi index yang diperbarui untuk mengirim data statistik
    public function index()
    {
        $user = Auth::user();

        // Menyiapkan data statistik berdasarkan relasi di model User
        // Asumsi: Relasi di model User bernama 'comics()'
        $stats = [
            'reading' => $user->comics()->where('reading_status', 'reading')->count(),
            'finished' => $user->comics()->where('reading_status', 'finished')->count(),
            'total_chapter' => $user->comics()->sum('last_read_chapter'),
        ];

        // Mengirim $user dan $stats ke view
        return view('user.profile', compact('user', 'stats'));
    }

    // Fungsi edit dan update tetap sama seperti milikmu...
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
        ]);

        $user->name = $request->name;
        $user->user_name = $request->user_name;

        if ($request->hasFile('profile_image')) {
            // 1. Hapus foto lama jika ada
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // 2. Simpan ke 'profiles' di disk 'public'
            // Hasilnya akan masuk ke storage/app/public/profiles
            $path = $request->file('profile_image')->store('profiles', 'public');

            // 3. Simpan path-nya ke database
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}

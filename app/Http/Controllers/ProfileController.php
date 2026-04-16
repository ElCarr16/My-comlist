<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // 1. Fungsi untuk MENAMPILKAN data profil saja
    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // 2. Fungsi untuk MENAMPILKAN HALAMAN FORM EDIT
    public function edit()
    {
        $user = Auth::user();
        return view('user.edit-profile', compact('user'));
    }

    // 3. Fungsi untuk MEMPROSES DATA yang dikirim dari form
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|alpha_dash|max:20|unique:users,user_name,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'user_name.unique' => 'Yah, username ini sudah dipakai orang lain. Cari yang lain ya!',
            'user_name.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).'
        ]);

        $user->name = $request->name;
        $user->user_name = $request->user_name;

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
    }
}

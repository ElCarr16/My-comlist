<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- FITUR REGISTER ---
    public function showRegister()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:255|unique:users',
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed', // Harus ada input password_confirmation di form
        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user', // Default register web selalu jadi user biasa
        ]);

        // Langsung otomatis login setelah register
        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di MyComList.');
    }

    // --- FITUR LOGIN ---
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            // Regenerate session untuk mencegah serangan Fixation
            $request->session()->regenerate();

            // Logika Redirect Berdasarkan Role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // User biasa langsung diarahkan ke Katalog Komik
            return redirect()->route('comics.index')->with('success', 'Selamat datang! Silakan temukan komik favoritmu.');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // --- FITUR LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda berhasil logout.');
    }
}

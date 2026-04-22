<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
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
            'password'  => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
        ]);

        Auth::login($user);
        return redirect()->route('user.dashboard')->with('success', 'Registrasi berhasil!');
    }

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
            $request->session()->regenerate();
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('comics.index')->with('success', 'Selamat datang!');
        }
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logout berhasil.');
    }

    // OTP PASSWORD RESET
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $otp = rand(100000, 999999);
        Cache::put("otp:{$request->email}", $otp, now()->addMinutes(10));
        try {
            Mail::to($request->email)->send(new OtpMail($otp));
            return redirect()->route('forgot.otp-input', $request->email)->with('status', 'OTP dikirim ke email!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal kirim email (lihat logs)');
        }
    }

    public function showOtpInput($email)
    {
        return view('auth.otp-input', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $email = $request->email;
        $cachedOtp = Cache::get("otp:{$email}");
        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->withErrors(['otp' => 'OTP salah atau kadaluarsa']);
        }
        session(['verified_otp' => $request->otp, 'reset_email' => $email]);
        Cache::forget("otp:{$email}");
        return redirect()->route('forgot.reset-password');
    }

    public function showResetPassword()
    {
        return view('auth.reset-password');
    }

    public function processResetPassword(Request $request)
    {
        $request->validate(['password' => 'required|min:8|confirmed']);
        $email = session('reset_email');
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        session()->forget(['verified_otp', 'reset_email']);
        return redirect()->route('login')->with('status', 'Password diubah!');
    }
}


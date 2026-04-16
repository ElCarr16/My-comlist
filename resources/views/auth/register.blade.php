@extends('layouts.app')

@section('title', 'Daftar - MyComList')

@section('content')
    <div
        style="max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; margin-bottom: 20px;">Buat Akun Baru</h2>

        <form action="{{ route('register.process') }}" method="POST">
            @csrf

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Username</label>
                <input type="text" name="user_name" value="{{ old('user_name') }}" required
                    style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                @error('user_name')
                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
                @error('email')
                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            <div style="margin-bottom: 15px; position: relative;">
                <label style="display: block; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" id="reg_password" required
                    style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; padding-right: 40px;">
                <button type="button" onclick="togglePassword('reg_password', 'eye-icon-1')"
                    style="position: absolute; right: 10px; top: 32px; background: none; border: none; cursor: pointer; font-size: 16px;">
                    <span id="eye-icon-1">👁️</span>
                </button>
                @error('password')
                    <span style="color: red; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px; position: relative;">
                <label style="display: block; margin-bottom: 5px;">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="reg_password_confirm" required
                    style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; padding-right: 40px;">
                <button type="button" onclick="togglePassword('reg_password_confirm', 'eye-icon-2')"
                    style="position: absolute; right: 10px; top: 32px; background: none; border: none; cursor: pointer; font-size: 16px;">
                    <span id="eye-icon-2">👁️</span>
                </button>
            </div>

            <button type="submit"
                style="width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Daftar
                Sekarang</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: #007bff; text-decoration: none;">Login di
                sini</a>
        </p>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerText = "🙈";
            } else {
                passwordInput.type = "password";
                eyeIcon.innerText = "👁️";
            }
        }
    </script>

@endsection

@extends('layouts.app')

@section('title', 'Login - MyComList')

@section('content')
<div style="max-width: 400px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 20px;">Masuk ke Akun</h2>

    @if($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px; position: relative;">
                <label style="display: block; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" id="password" required
                    style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; padding-right: 40px;">

                <button type="button" onclick="togglePassword('password', 'eye-icon')"
                    style="position: absolute; right: 10px; top: 32px; background: none; border: none; cursor: pointer; font-size: 16px;">
                    <span id="eye-icon">👁️</span>
                </button>
            </div>
        <button type="submit" style="width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Login</button>
    </form>
    
    <p style="text-align: center; margin-top: 15px; font-size: 14px;">
        Belum punya akun? <a href="{{ route('register') }}" style="color: #007bff; text-decoration: none;">Daftar di sini</a>
    </p>
</div>
@endsection
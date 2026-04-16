@extends('layouts.app')

@section('title', 'Selamat Datang di MyComList')

@section('content')
<div style="text-align: center; padding: 100px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; margin-bottom: 50px;">
    <h1 style="font-size: 3.5rem; margin-bottom: 20px;">Pantau Bacaan Komikmu di MyComList</h1>
    <p style="font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9;">Daftar, baca, dan beri skor pada manga, manhwa, atau manhua favoritmu di satu tempat.</p>
    
    @guest
        <div>
            <a href="{{ route('register') }}" style="padding: 15px 30px; background: #fff; color: #764ba2; text-decoration: none; border-radius: 30px; font-weight: bold; margin-right: 15px; display: inline-block;">Mulai Sekarang (Gratis)</a>
            <a href="{{ route('login') }}" style="padding: 15px 30px; border: 2px solid #fff; color: #fff; text-decoration: none; border-radius: 30px; font-weight: bold; display: inline-block;">Login</a>
        </div>
    @else
        <a href="{{ route('user.dashboard') }}" style="padding: 15px 30px; background: #fff; color: #764ba2; text-decoration: none; border-radius: 30px; font-weight: bold;">Ke Dashboard Saya</a>
    @endguest
</div>

<div style="max-width: 1200px; margin: 0 auto;">
    <h2 style="text-align: center; margin-bottom: 40px;">Kenapa Menggunakan MyComList?</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
            <div style="font-size: 40px; margin-bottom: 15px;">📚</div>
            <h3>Manajemen List</h3>
            <p>Simpan daftar komik yang ingin kamu baca, sedang dibaca, atau sudah selesai.</p>
        </div>
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
            <div style="font-size: 40px; margin-bottom: 15px;">⭐</div>
            <h3>Sistem Skor</h3>
            <p>Berikan penilaian pada komik yang kamu baca untuk mengingat mana yang terbaik.</p>
        </div>
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center;">
            <div style="font-size: 40px; margin-bottom: 15px;">🚀</div>
            <h3>Update Progress</h3>
            <p>Catat chapter terakhir yang kamu baca agar tidak lupa saat lanjut membaca nanti.</p>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Selamat Datang di MyComList')

@section('styles')
<style>
    .hero-welcome {
        min-height: 80vh;
        background-image: 
            linear-gradient(to right, rgba(15, 15, 15, 0.9), rgba(15, 15, 15, 0.2)), 
            url("{{ asset('assets/images/background.jpg') }}");
        background-size: cover;
        background-position: center;
        border-radius: 30px;
        padding: 60px;
        display: flex;
        align-items: center;
        margin-top: 10px;
        position: relative;
        overflow: hidden;
        border: 1px solid #222;
    }

    /* MEDIA QUERIES UNTUK HP */
    @media (max-width: 768px) {
        .hero-welcome {
            padding: 40px 20px;
            min-height: auto; /* Jangan paksa tinggi 80vh di HP */
            aspect-ratio: 16 / 9; /* PAKSA rasio gambar tetap seperti desktop (16:9) */
            background-size: 100% 100%; /* Paksa gambar muat 100% lebar dan tinggi */
            border-radius: 15px;
            margin-top: 5px;
            text-align: center;
            background-image: 
                linear-gradient(rgba(15, 15, 15, 0.7), rgba(15, 15, 15, 0.7)), 
                url("{{ asset('assets/images/background.jpg') }}");
        }

        .hero-welcome h1 {
            font-size: 1.8rem; /* Perkecil teks agar tidak menutupi gambar */
            margin-bottom: 0.5rem;
        }

        .hero-welcome p.lead {
            font-size: 0.85rem;
            margin-bottom: 1rem;
            display: -webkit-box; /* Batasi jumlah baris teks di HP agar gambar kelihatan */
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-orange {
            padding: 10px 20px;
            font-size: 0.9rem;
            width: auto; /* Di HP, jangan full-width jika gambarnya pendek */
        }
    }
</style>
@endsection

@section('content')
<div class="container mb-5 px-3"> {{-- Tambah px-3 supaya gak mepet pinggir HP --}}
    
    {{-- HERO SECTION --}}
    <section class="hero-welcome shadow-lg mb-5">
        <div class="row w-100 align-items-center m-0"> {{-- m-0 hapus margin row yang bikin geser --}}
            <div class="col-lg-7 p-0">
                <p class="text-warning fw-bold text-uppercase mb-2" style="letter-spacing: 2px; font-size: 0.75rem;">Platform Komik Modern</p>
                <h1 class="text-white mb-3">
                    Pantau Bacaan<br>Komikmu.
                </h1>
                <p class="lead mb-4 mx-auto mx-lg-0 text-secondary" style="max-width: 500px;">
                    Daftar, baca, dan beri skor pada manga, manhwa, atau manhua favoritmu dalam satu tempat.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-orange px-lg-5">
                            Mulai Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn btn-orange px-lg-5">
                            Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    <section class="py-2">
        <h4 class="fw-bold mb-4 text-center text-md-start">Kenapa MyComList?</h4>
        
        <div class="row g-3"> {{-- g-3 kecilin jarak antar kolom biar rapi di HP --}}
            <div class="col-12 col-md-4">
                <div class="card-custom p-4 h-100 border border-secondary border-opacity-10">
                    <div class="feature-icon mx-auto mx-md-0">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Manajemen List</h5>
                    <p class="text-secondary small mb-0">
                        Simpan daftar komik yang ingin kamu baca atau sudah selesai dalam satu klik.
                    </p>
                </div>
            </div>

            <div class="col-12 col-md-4 text-center text-md-start">
                <div class="card-custom p-4 h-100 border border-secondary border-opacity-10">
                    <div class="feature-icon mx-auto mx-md-0">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Sistem Skor</h5>
                    <p class="text-secondary small mb-0">
                        Berikan rating personal untuk setiap judul komik terbaik versimu.
                    </p>
                </div>
            </div>

            <div class="col-12 col-md-4 text-center text-md-start">
                <div class="card-custom p-4 h-100 border border-secondary border-opacity-10">
                    <div class="feature-icon mx-auto mx-md-0">
                        <i class="bi bi-rocket-takeoff-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Update Progress</h5>
                    <p class="text-secondary small mb-0">
                        Catat chapter terakhir agar tidak lupa kemarin baca sampai mana.
                    </p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
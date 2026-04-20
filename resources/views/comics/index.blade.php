@extends('layouts.app')

@section('title', 'Katalog Komik - MyComList')

{{-- CSS Tambahan Khusus Halaman Katalog --}}
@section('styles')
    <style>
        /* Section Utama Katalog */
        .katalog-section {
            background-color: #111;
            /* Hitam pekat agar kontras dengan card */
            border-radius: 30px;
            padding: 40px;
            margin-top: 20px;
        }

        /* Custom Input untuk Filter agar warnanya abu-abu gelap elegan */
        .filter-input {
            background-color: #1a1a1a !important;
            color: #fff !important;
            border: 1px solid #333 !important;
            border-radius: 12px;
        }

        .filter-input:focus {
            border-color: #ff4d00 !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 77, 0, 0.25) !important;
        }

        .filter-input::placeholder {
            color: #ffffff !important;
            opacity: 0.7;
            /* Sedikit diturunkan opacity-nya agar bisa dibedakan dengan teks input */
        }

        @media (max-width: 768px) {
            .katalog-section {
                padding: 25px 15px;
                border-radius: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="container mb-5">
        <div class="katalog-section shadow-lg">
            <h3 class="fw-bold mb-4">Katalog Komik</h3>

            {{-- SINI KITA PANGGIL KOMPONENNYA --}}
            <livewire:comic-search />
        </div>
    </section>
@endsection

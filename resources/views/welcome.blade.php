@extends('layouts.app')

@section('title', 'Selamat Datang di MyComList')

@section('styles')
    <style>
        :root {
            --brand-orange: #ff6b00;
            --brand-dark: #0f0f0f;
            --card-bg: #1a1a1a;
        }

        /* HERO SECTION OPTIMIZATION */
        .hero-welcome {
            min-height: 75vh;
            background:
                linear-gradient(to bottom, rgba(15, 15, 15, 0.6), rgba(15, 15, 15, 0.95)),
                linear-gradient(to right, rgba(15, 15, 15, 0.9), rgba(15, 15, 15, 0.1)),
                url("{{ asset('assets/images/background.jpg') }}");
            background-size: cover;
            background-position: center;
            border-radius: 24px;
            padding: 80px 60px;
            display: flex;
            align-items: center;
            margin-top: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
        }

        .hero-content {
            max-width: 600px;
            z-index: 2;
        }

        .hero-welcome h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
        }

        /* FEATURE CARDS */
        .card-feature {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 30px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-feature:hover {
            transform: translateY(-8px);
            border-color: var(--brand-orange);
            background: #222;
        }

        .feature-icon-circle {
            width: 50px;
            height: 50px;
            background: rgba(255, 107, 0, 0.1);
            color: var(--brand-orange);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .btn-orange-glow {
            background: var(--brand-orange);
            color: white;
            font-weight: 600;
            padding: 12px 32px;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
        }

        .btn-orange-glow:hover {
            background: #e66000;
            color: white;
            transform: scale(1.03);
            box-shadow: 0 6px 20px rgba(255, 107, 0, 0.4);
        }

        /* RESPONSIVE MOBILE (HP) */
        @media (max-width: 768px) {
            .hero-welcome {
                /* Kunci rasio 16:9 */
                aspect-ratio: 16 / 9;
                min-height: auto;
                /* Matikan tinggi minimal agar mengikuti rasio */

                padding: 20px;
                /* Perkecil padding agar konten muat */
                margin-top: 10px;
                border-radius: 15px;
                text-align: center;
                justify-content: center;

                /* Gambar tetap penuh tanpa gepeng */
                background-size: cover;
                background-position: center;
            }

            .hero-welcome h1 {
                font-size: 1.4rem;
                /* Kecilkan judul agar muat di rasio 16:9 */
                line-height: 1.2;
                margin-bottom: 0.5rem;
            }

            .hero-welcome p.lead {
                font-size: 0.75rem;
                /* Kecilkan teks penjelasan */
                margin-bottom: 1rem;
                /* Batasi hanya muncul 2 baris agar tidak memenuhi banner */
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .btn-group-mobile {
                flex-direction: row;
                /* Biarkan tombol sejajar ke samping jika muat */
                justify-content: center;
                gap: 8px;
            }

            .btn-orange-glow {
                padding: 8px 16px;
                font-size: 0.8rem;
                width: auto;
                /* Tombol tidak full-width agar hemat ruang */
            }
        }
    </style>
@endsection

@section('content')
    <div class="container px-3">
        {{-- HERO SECTION --}}
        <section class="hero-welcome shadow-2xl mb-5">
            <div class="hero-content">
                <p class="text-orange fw-bold text-uppercase mb-3"
                    style="letter-spacing: 3px; font-size: 0.8rem; color: var(--brand-orange);">
                    <i class="bi bi-lightning-charge-fill me-1"></i> Platform Komik Modern
                </p>
                <h1 class="text-white mb-3">
                    Pantau Bacaan<br><span style="color: var(--brand-orange);">Komikmu.</span>
                </h1>
                <p class="lead mb-4 text-white-50">
                    Daftar, baca, dan beri skor pada manga, manhwa, atau manhua favoritmu dalam satu tempat yang nyaman.
                </p>

                <div class="btn-group-mobile d-md-flex gap-3">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-orange-glow">
                            Daftar Sekarang <i class="bi bi-chevron-right small ms-1"></i>
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light px-4 py-2" style="border-radius: 12px;">
                            Login
                        </a>
                    @else
                        <a href="{{ route('comics.index') }}" class="btn btn-orange-glow">
                            Masuk Katalog <i class="bi bi-grid-fill ms-2"></i>
                        </a>
                    @endguest
                </div>
            </div>
        </section>

        {{-- FEATURES SECTION --}}
        <section class="py-4">
            <div class="d-flex align-items-center mb-4">
                <h4 class="fw-bold m-0">Kenapa MyComList?</h4>
                <div class="ms-3 flex-grow-1 bg-secondary opacity-10" style="height: 1px;"></div>
            </div>

            <div class="row g-4">
                <div class="col-12 col-md-4">
                    <div class="card-feature">
                        <div class="feature-icon-circle">
                            <i class="bi bi-collection-play"></i>
                        </div>
                        <h5 class="text-white fw-bold">Manajemen List</h5>
                        <p class="text-secondary small mb-0">
                            Kelola koleksi komik 'Sedang Dibaca', 'Selesai', atau 'Ingin Dibaca' dengan sistem folder yang
                            rapi.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card-feature">
                        <div class="feature-icon-circle">
                            <i class="bi bi-star-half"></i>
                        </div>
                        <h5 class="text-white fw-bold">Sistem Skor</h5>
                        <p class="text-secondary small mb-0">
                            Komunitas global! Berikan rating dan lihat skor rata-rata dari ribuan pembaca lainnya.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card-feature">
                        <div class="feature-icon-circle">
                            <i class="bi bi-plus-circle-dotted"></i>
                        </div>
                        <h5 class="text-white fw-bold">Update Progress</h5>
                        <p class="text-secondary small mb-0">
                            Jangan pernah lupa chapter terakhir. Update progres bacaanmu secara instan di setiap judul.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

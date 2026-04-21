<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyComList')</title>

    {{-- FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

    {{-- BOOTSTRAP --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    {{-- SLOT UNTUK CSS KHUSUS HALAMAN (PENTING!) --}}
    @yield('styles')

    {{-- WAJIB: STYLE DARI LIVEWIRE --}}
    @livewireStyles

    <style>
        /* BASE STYLES */
        body {
            font-family: 'Inter', sans-serif;
            background: #0f0f0f;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR */
        .navbar {
            background: transparent;
        }

        .nav-link {
            color: #ccc !important;
            font-size: 0.9rem;
            transition: 0.3s;
            font-weight: 600;
        }

        .nav-link:hover {
            color: white !important;
        }

        /* BUTTONS */
        .btn-orange {
            background: #ff4d00;
            color: white;
            border-radius: 50px;
            padding: 10px 25px;
            transition: 0.3s;
            border: none;
            font-weight: 800;
        }

        .btn-orange:hover {
            background: #e84300;
            color: white;
            transform: scale(1.05);
        }

        /* REUSABLE CLASSES */
        .dark-section {
            background: #111;
            border-radius: 30px;
            padding: 50px;
        }

        .card-custom {
            background: #1a1a1a;
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #222;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            border-color: #333;
        }

        /* PAGINATION STYLING (Agar matching dengan Dark Mode) */
        .pagination .page-link {
            background-color: #1a1a1a;
            border-color: #333;
            color: #fff;
        }

        .pagination .page-item.active .page-link {
            background-color: #ff4d00;
            border-color: #ff4d00;
        }

        /* Desain Card Komik Minimalis */
        .comic-card {
            background-color: #121212;
            /* Hitam yang lebih pekat & bersih */
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 18px;
            transition: all 0.3s ease;
        }

        .comic-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .comic-image-wrapper {
            border-radius: 18px 18px 0 0;
            overflow: hidden;
        }

        .comic-cover {
            transition: transform 0.5s ease;
        }

        .comic-card:hover .comic-cover {
            transform: scale(1.08);
            /* Efek zoom halus pada gambar */
        }

        .glass-badge {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(6px);
            /* Efek kaca */
            border: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 600;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg px-4 py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-white fs-4" href="{{ url('/') }}" style="letter-spacing: -1px;">
                MyComList
            </a>

            <button class="navbar-toggler text-white border-0" data-bs-toggle="collapse" data-bs-target="#nav">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto align-items-center gap-3 text-uppercase"
                    style="font-size: 0.75rem; letter-spacing: 1px;">
                    <li><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li><a class="nav-link" href="{{ route('comics.index') }}">Komik</a></li>

                    @guest
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-person-circle fs-5"></i>
                            </a>
                            <ul
                                class="dropdown-menu dropdown-menu-end dropdown-menu-dark bg-dark border-secondary shadow-lg mt-2">
                                <li><a class="dropdown-item py-2" href="{{ route('login') }}"><i
                                            class="bi bi-box-arrow-in-right me-2"></i> Sign In</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('register') }}"><i
                                            class="bi bi-person-plus me-2"></i> Sign Up</a></li>
                            </ul>
                        </li>
                    @else
                        <li><a class="nav-link" href="{{ route('user.dashboard') }}">Koleksi</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white fw-bold d-flex align-items-center gap-2"
                                href="#" id="userMenu" role="button" data-bs-toggle="dropdown"
                                style="text-transform: none;">

                                {{-- KODE NAVBAR AVATAR --}}
                                @if (Auth::user()->profile_image)
                                    <img src="{{ route('profile.image.view', basename(Auth::user()->profile_image)) }}"
                                        class="rounded-circle"
                                        style="width: 28px; height: 28px; object-fit: cover; border: 1px solid #ff4d00;">
                                @else
                                    <i class="bi bi-person-circle fs-5"></i>
                                @endif

                                {{ Auth::user()->user_name ?? Auth::user()->name }}
                            </a>

                            <ul
                                class="dropdown-menu dropdown-menu-end dropdown-menu-dark bg-dark border-secondary shadow-lg mt-2">
                                <li><a class="dropdown-item py-2" href="{{ route('user.profile') }}"><i
                                            class="bi bi-person me-2"></i> Profil Saya</a></li>
                                <li>
                                    <hr class="dropdown-divider bg-secondary">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold py-2"><i
                                                class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- AREA KONTEN DINAMIS --}}
    <main class="flex-grow-1">
        @yield('content')
    </main>

    {{-- SCRIPT BOOTSTRAP & CUSTOM SCRIPTS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')

    {{-- WAJIB: SCRIPT DARI LIVEWIRE (Harus diletakkan sebelum tag body tertutup) --}}
    @livewireScripts
</body>

</html>

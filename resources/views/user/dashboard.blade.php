@extends('layouts.app')

@section('title', 'Koleksi Saya - MyComList')

@section('styles')
    <style>
        /* Styling kustom untuk Tabs agar sesuai dengan tema Dark/Orange */
        .nav-pills .nav-link {
            color: #ccc;
            background-color: #1a1a1a;
            border: 1px solid #333;
            transition: 0.3s;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: #fff !important;
            background-color: #ff4d00 !important;
            border-color: #ff4d00 !important;
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: #222;
            color: #fff;
        }

        .badge-status {
            font-size: 0.7rem;
            letter-spacing: 1px;
            padding: 0.5em 0.8em;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4 mb-5">
        <div class="dark-section shadow-lg p-4 p-md-5">

            {{-- HEADER --}}
            <div
                class="d-flex justify-content-between align-items-center mb-5 pb-4 border-bottom border-secondary border-opacity-25 flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1">Koleksi Saya</h3>
                    <p class="text-secondary mb-0 small text-uppercase" style="letter-spacing: 1px;">Pantau progres bacaan
                        kamu</p>
                </div>

                <a href="{{ route('user.profile') }}"
                    class="btn btn-dark border border-secondary rounded-pill px-4 shadow-sm text-white fw-bold">
                    <i class="bi bi-gear-fill me-2"></i> Pengaturan
                </a>
            </div>

            {{-- STATS BOX --}}
            <div class="card-custom p-4 mb-5 shadow border border-dark text-center">
                <div class="row g-4">
                    <div class="col-6 col-md-3 border-end border-secondary border-opacity-25">
                        <i class="bi bi-journal-bookmark-fill mb-2 fs-4" style="color: #ff4d00;"></i>
                        <small class="text-secondary d-block text-uppercase mb-1" style="font-size: 0.7rem;">Tracked</small>
                        <div class="fw-bold text-white fs-4">{{ $stats['total_tracked'] }}</div>
                    </div>
                    <div class="col-6 col-md-3 border-end-md border-secondary border-opacity-25">
                        <i class="bi bi-check-circle-fill mb-2 fs-4 text-success"></i>
                        <small class="text-secondary d-block text-uppercase mb-1" style="font-size: 0.7rem;">Tamat</small>
                        <div class="fw-bold text-white fs-4">{{ $stats['completed'] }}</div>
                    </div>
                    <div class="col-6 col-md-3 border-end border-secondary border-opacity-25">
                        <i class="bi bi-star-fill mb-2 fs-4 text-warning"></i>
                        <small class="text-secondary d-block text-uppercase mb-1" style="font-size: 0.7rem;">Rata-rata
                            Rating</small>
                        <div class="fw-bold text-white fs-4">{{ $stats['avg_score'] }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <i class="bi bi-heart-fill mb-2 fs-4 text-danger"></i>
                        <small class="text-secondary d-block text-uppercase mb-1" style="font-size: 0.7rem;">Disukai</small>
                        <div class="fw-bold text-white fs-4">{{ $stats['total_liked'] }}</div>
                    </div>
                </div>
            </div>

            {{-- TABS --}}
            <ul class="nav nav-pills mb-4 gap-2 border-bottom border-secondary border-opacity-25 pb-4" id="collectionTabs"
                role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold px-4 py-2 shadow-sm" id="list-tab"
                        data-bs-toggle="pill" data-bs-target="#list" type="button" role="tab">
                        <i class="bi bi-list-ul me-1"></i> My List
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold px-4 py-2 shadow-sm" id="liked-tab" data-bs-toggle="pill"
                        data-bs-target="#liked" type="button" role="tab">
                        <i class="bi bi-heart-fill me-1"></i> Disukai
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="collectionTabsContent">

                {{-- TAB: MY LIST --}}
                <div class="tab-pane fade show active pt-2" id="list" role="tabpanel" tabindex="0">

                    @if (session('success'))
                        <div class="alert bg-dark text-success border-0 border-start border-success border-4 shadow mb-4"
                            style="border-radius: 12px;">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="row g-3">
                        @forelse($trackedComics as $comic)
                            <div class="col-md-6 col-xl-4">
                                <div
                                    class="card-custom p-3 h-100 d-flex gap-3 align-items-center border border-secondary border-opacity-25">

                                    {{-- COVER --}}
                                    <a href="{{ route('comics.show', $comic->slug) }}"
                                        class="text-decoration-none flex-shrink-0">
                                        <div
                                            style="width: 80px; height: 110px; border-radius: 10px; overflow: hidden; background: #111; border: 1px solid #333;">
                                            @if ($comic->cover_image)
                                                <img src="{{ asset('storage/' . $comic->cover_image) }}" class="w-100 h-100"
                                                    style="object-fit:cover;">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary"
                                                    style="font-size: 0.6rem;">NO COVER</div>
                                            @endif
                                        </div>
                                    </a>

                                    {{-- INFO --}}
                                    <div class="flex-grow-1 min-w-0">
                                        <a href="{{ route('comics.show', $comic->slug) }}"
                                            class="text-decoration-none text-white">
                                            <div class="fw-bold text-truncate mb-1">{{ $comic->title }}</div>
                                        </a>

                                        <div class="text-secondary small mb-3 d-flex align-items-center gap-3">
                                            <span title="Progress Chapter"><i class="bi bi-book me-1"></i>
                                                {{ $comic->pivot->last_read_chapter }} /
                                                {{ $comic->total_chapter }}</span>
                                            @if ($comic->pivot->score)
                                                <span class="text-warning"><i class="bi bi-star-fill me-1"></i>
                                                    {{ $comic->pivot->score }}</span>
                                            @endif
                                        </div>

                                        {{-- STATUS --}}
                                        @php
                                            $statusColor = [
                                                'reading' => 'warning',
                                                'completed' => 'success',
                                                'plan_to_read' => 'secondary',
                                                'dropped' => 'danger',
                                            ];
                                            $bgColor = $statusColor[$comic->pivot->reading_status] ?? 'secondary';
                                        @endphp
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span
                                                class="badge bg-{{ $bgColor }} badge-status text-uppercase rounded-pill text-dark fw-bold">
                                                {{ str_replace('_', ' ', $comic->pivot->reading_status) }}
                                            </span>
                                            <a href="{{ route('comics.show', $comic->slug) }}"
                                                class="btn btn-sm btn-dark border border-secondary text-white rounded-circle shadow-sm"
                                                title="Update Progress">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-secondary py-5">
                                <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                                <p class="fs-5">Belum ada komik di list kamu.</p>
                                <a href="{{ route('comics.index') }}"
                                    class="btn btn-orange rounded-pill px-4 mt-2 fw-bold">
                                    Cari Komik Sekarang
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- TAB: LIKED --}}
                <div class="tab-pane fade pt-2" id="liked" role="tabpanel" tabindex="0">
                    <div class="row g-3 g-md-4">
                        @forelse($user->likedComics as $comic)
                            <div class="col-6 col-md-3 col-lg-2">
                                <div class="card-custom h-100 shadow-sm border-0">

                                    {{-- COVER --}}
                                    <a href="{{ route('comics.show', $comic->slug) }}" class="text-decoration-none">
                                        @if ($comic->cover_image)
                                            <img src="{{ asset('storage/' . $comic->cover_image) }}" class="w-100"
                                                style="height:240px; object-fit:cover;">
                                        @else
                                            <div class="bg-dark d-flex align-items-center justify-content-center text-secondary"
                                                style="height:240px; font-size: 0.8rem; border-bottom: 1px solid #222;">
                                                NO COVER
                                            </div>
                                        @endif
                                    </a>

                                    {{-- TITLE & LIKE --}}
                                    <div class="p-3 text-center">
                                        <a href="{{ route('comics.show', $comic->slug) }}"
                                            class="text-decoration-none text-white">
                                            <span class="fw-bold d-block text-truncate mb-2" style="font-size: 0.9rem;">
                                                {{ $comic->title }}
                                            </span>
                                        </a>
                                        <span class="text-danger fw-bold" style="font-size: 0.8rem;">
                                            <i class="bi bi-heart-fill me-1"></i> Disukai
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-secondary py-5">
                                <i class="bi bi-heartbreak fs-1 d-block mb-3"></i>
                                <p class="fs-5">Belum ada komik yang disukai.</p>
                                <a href="{{ route('comics.index') }}"
                                    class="btn btn-outline-light rounded-pill px-4 mt-2">
                                    Jelajahi Katalog
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@extends('layouts.app')

@section('title', 'Koleksi Saya - MyComList')

@section('content')

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Koleksi Saya</h4>
        <small class="text-secondary">Pantau progres bacaan kamu</small>
    </div>

    <a href="{{ route('user.profile') }}" class="btn btn-outline-light rounded-pill px-3">
        <i class="bi bi-gear"></i>
    </a>
</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center">
            <div class="fw-bold fs-4">{{ $stats['total_tracked'] }}</div>
            <small class="text-secondary">Total</small>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center">
            <div class="fw-bold fs-4">{{ $stats['completed'] }}</div>
            <small class="text-secondary">Tamat</small>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center">
            <div class="fw-bold fs-4 text-warning">
                <i class="bi bi-star-fill"></i> {{ $stats['avg_score'] }}
            </div>
            <small class="text-secondary">Rating</small>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card-custom p-3 text-center">
            <div class="fw-bold fs-4 text-danger">
                <i class="bi bi-heart-fill"></i> {{ $stats['total_liked'] }}
            </div>
            <small class="text-secondary">Disukai</small>
        </div>
    </div>
</div>

{{-- TABS --}}
<ul class="nav mb-4">
    <li class="nav-item">
        <button class="btn btn-orange me-2" data-bs-toggle="tab" data-bs-target="#list">
            My List
        </button>
    </li>
    <li class="nav-item">
        <button class="btn btn-outline-light" data-bs-toggle="tab" data-bs-target="#liked">
            Disukai
        </button>
    </li>
</ul>

<div class="tab-content">

    {{-- LIST --}}
    <div class="tab-pane fade show active" id="list">

        @if(session('success'))
            <div class="alert alert-success rounded-pill">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-3">

            @forelse($trackedComics as $comic)
            <div class="col-md-6">
                <div class="card-custom p-3 d-flex gap-3 align-items-center">

                    {{-- COVER --}}
                    <div style="width:70px;height:90px;flex-shrink:0;">
                        @if($comic->cover_image)
                            <img src="{{ asset('storage/' . $comic->cover_image) }}"
                                 class="w-100 h-100 rounded"
                                 style="object-fit:cover;">
                        @else
                            <div class="bg-secondary w-100 h-100 d-flex align-items-center justify-content-center rounded small">
                                No Img
                            </div>
                        @endif
                    </div>

                    {{-- INFO --}}
                    <div class="flex-grow-1">
                        <div class="fw-semibold">{{ $comic->title }}</div>

                        <small class="text-secondary d-block">
                            Ch {{ $comic->pivot->last_read_chapter }} / {{ $comic->total_chapter }}
                        </small>

                        {{-- STATUS --}}
                        @php
                            $statusColor = [
                                'reading' => 'warning',
                                'completed' => 'success',
                                'plan_to_read' => 'secondary',
                                'dropped' => 'danger'
                            ];
                        @endphp

                        <span class="badge bg-{{ $statusColor[$comic->pivot->reading_status] ?? 'secondary' }}">
                            {{ str_replace('_', ' ', $comic->pivot->reading_status) }}
                        </span>
                    </div>

                    {{-- ACTION --}}
                    <div>
                        <a href="{{ route('comics.show', $comic->slug) }}"
                           class="btn btn-sm btn-outline-light">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>

                </div>
            </div>
            @empty
            <div class="col-12 text-center text-secondary py-5">
                Belum ada komik<br>
                <a href="{{ route('comics.index') }}" class="btn btn-orange mt-3">
                    Cari Komik
                </a>
            </div>
            @endforelse

        </div>
    </div>

    {{-- LIKED --}}
    <div class="tab-pane fade" id="liked">

        <div class="row g-3">

            @forelse($user->likedComics as $comic)
            <div class="col-4 col-md-2">
                <div class="card-custom">

                    @if($comic->cover_image)
                        <img src="{{ asset('storage/' . $comic->cover_image) }}"
                             class="w-100"
                             style="height:180px;object-fit:cover;">
                    @endif

                    <div class="p-2 text-center">
                        <small class="d-block text-truncate">{{ $comic->title }}</small>
                        <i class="bi bi-heart-fill text-danger"></i>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-secondary py-5">
                Belum ada yang disukai
            </div>
            @endforelse

        </div>

    </div>

</div>

@endsection
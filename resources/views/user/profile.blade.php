@extends('layouts.app')

@section('title', 'Profil Saya - MyComList')

@section('content')

    <div class="row justify-content-center mt-4">
        <div class="col-lg-6">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h4 class="fw-bold mb-0 text-white">Profil</h4>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light rounded-pill px-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>

            {{-- PROFILE CARD --}}
            <div class="card-custom p-4 text-center text-white mb-4">
                <div class="mb-4">
                    @if ($user->profile_image)
                        {{-- Kita memanggil rute view foto, bukan path langsung --}}
                        <img src="{{ route('profile.image.view', basename($user->profile_image)) }}"
                            class="rounded-circle shadow-sm"
                            style="width:120px;height:120px;object-fit:cover; border: 3px solid #ff4d00;"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}';">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            style="width:120px;height:120px;background:#222;font-size:40px; border: 3px solid #ff4d00;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h5 class="fw-bold mb-1">{{ $user->user_name ? '@' . $user->user_name : $user->name }}</h5>
                <p class="text-white-50 small mb-4"><i class="bi bi-envelope"></i> {{ $user->email }}</p>

                {{-- STATISTIK --}}
                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <div class="p-2 bg-dark rounded-3 border border-secondary">
                            <h6 class="fw-bold mb-0">{{ $stats['reading'] }}</h6>
                            <small class="text-secondary" style="font-size: 0.7rem;">Dibaca</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 bg-dark rounded-3 border border-secondary">
                            <h6 class="fw-bold mb-0">{{ $stats['finished'] }}</h6>
                            <small class="text-secondary" style="font-size: 0.7rem;">Selesai</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 bg-dark rounded-3 border border-secondary">
                            <h6 class="fw-bold mb-0">{{ $stats['total_chapter'] }}</h6>
                            <small class="text-secondary" style="font-size: 0.7rem;">Chapter</small>
                        </div>
                    </div>
                </div>

                <a href="{{ route('user.profile.edit') }}" class="btn btn-orange w-100 mb-3">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profil
                </a>
            </div>

            {{-- KOMIK TERAKHIR --}}
            <h6 class="text-white-50 fw-bold mb-3 px-2">Aktivitas Terakhir</h6>
            <div class="card-custom bg-dark p-3">
                @forelse($user->comics()->latest()->limit(3)->get() as $comic)
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ Str::startsWith($comic->cover_image, 'http') ? $comic->cover_image : asset('storage/' . $comic->cover_image) }}"
                            style="width: 50px; height: 70px; object-fit: cover;" class="rounded">
                        <div class="ms-3 text-start">
                            <div class="fw-bold text-white small">{{ $comic->title }}</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">Chapter
                                {{ $comic->pivot->last_read_chapter }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-secondary small text-center py-3">Belum ada aktivitas membaca.</p>
                @endforelse
            </div>

        </div>
    </div>
@endsection

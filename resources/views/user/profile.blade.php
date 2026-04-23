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

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- SECTION AKUN MYANIMELIST --}}
            <div class="card-custom bg-dark p-3 mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-white fw-bold mb-0">Akun MyAnimeList Terintegrasi</h6>
                    <button class="btn btn-sm btn-orange fw-bold" data-bs-toggle="modal" data-bs-target="#addMalModal">
                        + Tambah Akun
                    </button>
                </div>

                {{-- DAFTAR AKUN YANG TERSIMPAN --}}
                @forelse ($user->trackedSources as $source)
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom border-secondary">
                        <div class="text-start">
                            <a href="{{ $source->url }}" class="text-white small fw-bold"
                                target="_blank">{{ $source->title }}</a>
                            <br>
                            <small class="text-success" style="font-size: 0.7rem;">
                                Status: {{ $source->last_chapter_title ?? 'Belum di-sync' }}
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('user.sync.mal.execute', $source->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info text-dark fw-bold px-2 py-1"
                                    style="font-size: 0.8rem;" title="Sync Sekarang">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </form>

                            <button type="button" class="btn btn-sm btn-warning text-dark fw-bold px-2 py-1"
                                data-bs-toggle="modal" data-bs-target="#editMalModal{{ $source->id }}"
                                style="font-size: 0.8rem;" title="Edit Akun">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <form action="{{ route('user.source.delete', $source->id) }}" method="POST" class="m-0">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger px-2 py-1" style="font-size: 0.8rem;"
                                    title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-secondary small text-center py-2">Belum ada akun terintegrasi.</p>
                @endforelse
            </div>

            {{-- AKTIVITAS TERAKHIR (INTERNAL) --}}
            <h6 class="text-white-50 fw-bold mb-3 px-2 mt-4">Aktivitas Terakhir</h6>
            <div class="card-custom bg-dark p-3">
                @forelse($user->trackedComics()->latest()->limit(3)->get() as $comic)
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

    {{-- ========================================== --}}
    {{-- AREA KHUSUS MODAL (Di luar dari Card/Layout) --}}
    {{-- ========================================== --}}

    {{-- MODAL TAMBAH AKUN MAL --}}
    <div class="modal fade" id="addMalModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('user.source.storeMal') }}" method="POST" class="modal-content bg-dark text-white">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title">Tambah Akun MyAnimeList</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="small text-white-50 mb-1">Username Akun MAL</label>
                    <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>

                    <label class="small text-white-50 mb-1">Link Profil MAL</label>
                    <input type="url" name="url" class="form-control"
                        placeholder="Contoh: https://myanimelist.net/profile/username" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-info w-100 fw-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- LOOPING KHUSUS UNTUK MODAL EDIT --}}
    @foreach ($user->trackedSources as $source)
        <div class="modal fade" id="editMalModal{{ $source->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('user.source.updateMal', $source->id) }}" method="POST"
                    class="modal-content bg-dark text-white">
                    @csrf @method('PUT')
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Edit Akun MyAnimeList</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="small text-white-50 mb-1">Username Akun MAL</label>
                        <input type="text" name="username" class="form-control mb-3"
                            value="{{ str_replace(' (MAL Account)', '', $source->title) }}" required>

                        <label class="small text-white-50 mb-1">Link Profil MAL</label>
                        <input type="url" name="url" class="form-control" value="{{ $source->url }}" required>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-warning w-100 fw-bold">Update Akun</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

@endsection

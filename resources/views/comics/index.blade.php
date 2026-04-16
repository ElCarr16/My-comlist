@extends('layouts.app')

@section('title', 'Katalog Komik - MyComList')

{{-- CSS Tambahan Khusus Halaman Katalog --}}
@section('styles')
<style>
    /* Section Utama Katalog */
    .katalog-section {
        background-color: #111; /* Hitam pekat agar kontras dengan card */
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

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Katalog Komik</h3>
                <p class="text-secondary mb-0 small text-uppercase" style="letter-spacing: 1px;">Temukan komik favoritmu</p>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="p-3 mb-5" style="background: #1a1a1a; border-radius: 20px; border: 1px solid #222;">
            <form action="{{ route('comics.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari komik..." class="form-control filter-input py-2">
                </div>
                <div class="col-md-2">
                    <select name="genre" class="form-select filter-input py-2">
                        <option value="">Semua Genre</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="year" value="{{ request('year') }}" placeholder="Tahun Rilis" class="form-control filter-input py-2">
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select filter-input py-2">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populer</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-orange w-100 py-2 fw-bold">Filter</button>
                    @if(request()->anyFilled(['search','genre','year','sort']))
                        <a href="{{ route('comics.index') }}" class="btn btn-outline-secondary w-100 py-2 text-white border-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- GRID KOMIK --}}
        <div class="row g-3 g-md-4">
            @forelse ($comics as $comic)
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card-custom h-100 shadow-sm border-0">
                    
                    {{-- COVER --}}
                    <a href="{{ route('comics.show', $comic->slug) }}" class="text-decoration-none">
                        @if ($comic->cover_image)
                            <img src="{{ asset('storage/' . $comic->cover_image) }}" class="w-100" style="height:240px; object-fit:cover;">
                        @else
                            <div class="bg-dark d-flex align-items-center justify-content-center text-secondary" style="height:240px; font-size: 0.8rem; border-bottom: 1px solid #222;">
                                NO COVER
                            </div>
                        @endif
                    </a>

                    <div class="p-3">
                        {{-- TITLE --}}
                        <a href="{{ route('comics.show', $comic->slug) }}" class="text-decoration-none text-white">
                            <span class="fw-bold d-block text-truncate mb-2" style="font-size: 0.9rem;">
                                {{ $comic->title }}
                            </span>
                        </a>

                        {{-- RATING + LIKE --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-warning fw-bold" style="font-size: 0.8rem;">
                                <i class="bi bi-star-fill me-1"></i>
                                {{ $comic->users_avg_comic_userscore ? number_format($comic->users_avg_comic_userscore,1) : '0.0' }}
                            </span>

                            @auth
                                @php
                                    $isLiked = auth()->user()->likedComics->contains($comic->id);
                                @endphp
                                <button onclick="toggleLike({{ $comic->id }}, this)" class="btn btn-sm p-0 border-0 text-danger d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                    <i class="bi {{ $isLiked ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                    <span class="like-count text-white-50">{{ $comic->liked_by_users_count ?? 0 }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="text-danger text-decoration-none d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-heart"></i>
                                    <span class="text-white-50">{{ $comic->liked_by_users_count ?? 0 }}</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-secondary py-5">
                <i class="bi bi-emoji-frown fs-1 mb-3 d-block"></i>
                <p class="fs-5">Yah, komik yang kamu cari tidak ditemukan.</p>
            </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-5 d-flex justify-content-center" data-bs-theme="dark">
            {{ $comics->links() }}
        </div>

    </div>
</section>

{{-- LIKE SCRIPT --}}
<script>
function toggleLike(id, el){
    fetch(`/comics/${id}/like`,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        }
    })
    .then(res=>res.json())
    .then(data=>{
        el.innerHTML = `
            <i class="bi ${data.isLiked ? 'bi-heart-fill' : 'bi-heart'}"></i>
            <span class="like-count text-white-50">${data.likesCount}</span>
        `;
    });
}
</script>
@endsection
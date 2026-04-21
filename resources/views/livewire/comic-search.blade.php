<div>

    {{-- FILTER UI --}}
    <div class="p-3 mb-4 shadow-sm" style="background: #1a1a1a; border-radius: 15px; border: 1px solid #222;">
        <div class="row g-2">
            <div class="col-12 col-md-3">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari komik..."
                    class="form-control filter-input">
            </div>
            <div class="col-6 col-md-2">
                <select wire:model.live="genre" class="form-select filter-input">
                    <option value="">Genre</option>
                    @foreach ($genres as $g)
                        <option value="{{ $g->id }}">{{ $g->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select wire:model.live="type" class="form-select filter-input">
                    <option value="">Tipe</option>
                    <option value="Manga">Manga</option>
                    <option value="Manhwa">Manhwa</option>
                    <option value="Manhua">Manhua</option>
                    <option value="One-shot">Oneshot</option>
                </select>
            </div>
            <div class="col-4 col-md-2">
                <input type="number" wire:model.live.debounce.300ms="year" placeholder="Tahun"
                    class="form-control filter-input px-2">
            </div>
            <div class="col-6 col-md-2">
                <div class="input-group">
                    <select wire:model.live="sort" class="form-select filter-input border-end-0">
                        <option value="latest">Terbaru</option>
                        <option value="popular">Populer</option>
                        <option value="rating">Rating</option>
                        <option value="name">A-Z</option>
                    </select>
                    <button wire:click="toggleDirection" class="btn btn-dark border-secondary border-opacity-25"
                        title="Balik Urutan">
                        <i class="bi {{ $sortOrder == 'desc' ? 'bi-sort-down' : 'bi-sort-up-alt' }}"></i>
                    </button>
                </div>
            </div>
            <div class="col-2 col-md-1">
                <button wire:click="resetFilters" class="btn btn-outline-danger w-100" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- WRAPPER GRID KOMIK DENGAN POSITION RELATIVE (BAGIAN YANG DITAMBAHKAN) --}}
    <div class="position-relative" style="min-height: 300px;">

        {{-- OVERLAY ANIMASI LOADING --}}
        <div wire:loading.flex
            class="position-absolute top-0 start-0 w-100 h-100 justify-content-center align-items-start pt-5"
            style="background-color: rgba(26, 26, 26, 0.7); z-index: 10; backdrop-filter: blur(2px); border-radius: 15px; display: none;">
            <div class="text-center">
                <div class="spinner-border text-warning mb-2" style="width: 3rem; height: 3rem;" role="status"></div>
                <div class="text-warning fw-bold small">Memuat Data...</div>
            </div>
        </div>

        {{-- GRID KOMIK (Tambahkan wire:loading.class agar meredup saat loading) --}}
        <div class="row g-3 g-md-4" wire:loading.class="opacity-50">
            @forelse ($comics as $comic)
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card-custom h-100 shadow-sm border-0 bg-dark rounded-3 overflow-hidden"
                        style="border: 1px solid #333 !important;">

                        <a href="{{ route('comics.show', $comic->slug) }}" class="d-block position-relative">
                            {{-- Badge dipindah ke atas agar tidak tertutup gambar --}}
                            <span
                                class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 text-warning z-1">
                                <i
                                    class="bi bi-star-fill me-1"></i>{{ $comic->avg_score ? number_format($comic->avg_score, 1) : '0.0' }}
                            </span>

                            @if ($comic->cover_image)
                                @if (Str::startsWith($comic->cover_image, ['http://', 'https://']))
                                    {{-- TAMBAHKAN loading="lazy" DI SINI --}}
                                    <img src="{{ $comic->cover_image }}" class="w-100"
                                        style="height:240px; object-fit:cover;" loading="lazy"
                                        alt="{{ $comic->title }}">
                                @else
                                    {{-- TAMBAHKAN loading="lazy" DI SINI --}}
                                    <img src="{{ asset('storage/' . $comic->cover_image) }}" class="w-100"
                                        style="height:240px; object-fit:cover;" loading="lazy"
                                        alt="{{ $comic->title }}">
                                @endif
                            @else
                                <div class="w-100 d-flex align-items-center justify-content-center bg-secondary text-white"
                                    style="height:240px;">NO COVER</div>
                            @endif
                        </a>

                        <div class="p-3">
                            <a href="{{ route('comics.show', $comic->slug) }}" class="text-decoration-none text-white">
                                <span class="fw-bold d-block text-truncate mb-2"
                                    style="font-size: 0.9rem;">{{ $comic->title }}</span>
                            </a>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-danger d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                    <i class="bi bi-heart-fill"></i><span
                                        class="text-white-50">{{ $comic->liked_by_users_count ?? 0 }}</span>
                                </span>
                                {{-- Saya kembalikan info tahun di sisi kanan agar lebih informatif --}}
                                <span class="text-secondary small">{{ $comic->release_year ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-secondary py-5">Komik tidak ditemukan.</div>
            @endforelse
        </div>
    </div> {{-- TUTUP WRAPPER GRID KOMIK --}}

    <div class="mt-5 d-flex justify-content-center" data-bs-theme="dark">
        {{ $comics->links() }}
    </div>
</div>

<div>
    {{-- HEADER & SEARCH BAR --}}
    <div class="d-flex align-items-center gap-4 mb-4">
        <h3 class="fw-bold mb-0">Katalog Komik</h3>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul komik..."
            class="form-control filter-input bg-dark text-white border-secondary flex-grow-1">
    </div>

    {{-- FILTER UI --}}
    <div class="p-3 mb-4 shadow-sm" style="background: #1a1a1a; border-radius: 15px; border: 1px solid #222;">
        <div class="row g-2 align-items-stretch">

            {{-- 1. GENRE --}}
            <div class="col-6 col-md-4 col-lg-2">
                <div class="dropdown h-100 d-flex">
                    <button class="btn btn-dark w-100 dropdown-toggle border-secondary flex-grow-1 d-flex align-items-center justify-content-between" type="button"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
                        style="background-color: #212529; color: #fff;">
                        <span>Genre ({{ count($selectedGenres) }})</span>
                    </button>
                    <div class="dropdown-menu p-3 bg-dark border-secondary shadow w-100"
                        style="max-height: 300px; overflow-y: auto;">
                        @foreach ($genres as $g)
                            <div class="form-check mb-2">
                                <input class="form-check-input bg-secondary border-secondary" type="checkbox"
                                    wire:model="selectedGenres" value="{{ $g->id }}" id="genre{{ $g->id }}">
                                <label class="form-check-label text-light"
                                    for="genre{{ $g->id }}">{{ $g->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. STATUS --}}
            <div class="col-6 col-md-4 col-lg-2">
                <select wire:model="filterStatus" class="form-select filter-input h-100 w-100 border-secondary bg-dark text-white">
                    <option value="">Status</option>
                    @foreach ($statusOptions as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 3. TIPE --}}
            <div class="col-6 col-md-4 col-lg-2">
                <select wire:model="type" class="form-select filter-input h-100 w-100 bg-dark text-white border-secondary">
                    <option value="">Tipe</option>
                    <option value="Manga">Manga</option>
                    <option value="Manhwa">Manhwa</option>
                    <option value="Manhua">Manhua</option>
                    <option value="One-shot">Oneshot</option>
                </select>
            </div>

            {{-- 4. TAHUN --}}
            <div class="col-6 col-md-3 col-lg-1">
                <input type="number" wire:model="year" placeholder="Tahun"
                    class="form-control filter-input h-100 w-100 px-2 bg-dark text-white border-secondary">
            </div>

            {{-- 5. SORTING --}}
            <div class="col-8 col-md-6 col-lg-3">
                <div class="input-group h-100 w-100">
                    <select wire:model="sort"
                        class="form-select filter-input border-end-0 h-100 bg-dark text-white border-secondary">
                        <option value="rating">Rating</option>
                        <option value="name">A-Z</option>
                        <option value="popular">Populer</option>
                        <option value="latest">Terbaru</option>
                    </select>
                    <button wire:click="toggleDirection" class="btn btn-dark border-start-0 border-secondary border-opacity-25 h-100 px-3"
                        title="Balik Urutan">
                        <i class="bi {{ $sortOrder == 'desc' ? 'bi-sort-down' : 'bi-sort-up-alt' }}"></i>
                    </button>
                </div>
            </div>

            {{-- 6. BUTTONS --}}
            <div class="col-4 col-md-3 col-lg-2">
                <div class="d-flex gap-1 w-100 h-100">
                    <button wire:click="applyFilters" class="btn btn-success flex-fill h-100" title="Terapkan">
                        <i class="bi bi-check-lg"></i>
                    </button>
                    <button wire:click="resetFilters" class="btn btn-outline-danger flex-fill h-100" title="Reset">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- WRAPPER GRID KOMIK --}}
    <div class="position-relative" style="min-height: 300px;">

        {{-- OVERLAY ANIMASI LOADING --}}
        <div wire:loading.flex
            wire:target="applyFilters, resetFilters, toggleDirection, gotoPage, nextPage, previousPage"
            class="position-absolute top-0 start-0 w-100 h-100 justify-content-center align-items-start pt-5"
            style="background-color: rgba(26, 26, 26, 0.7); z-index: 10; backdrop-filter: blur(2px); border-radius: 15px; display: none;">
            <div class="text-center">
                <div class="spinner-border text-success mb-2" style="width: 3rem; height: 3rem;" role="status"></div>
                <div class="text-success fw-bold small">Menerapkan...</div>
            </div>
        </div>

        {{-- GRID KOMIK --}}
        <div class="row g-3 g-md-4" wire:loading.class="opacity-50"
            wire:target="applyFilters, resetFilters, toggleDirection, gotoPage">
            @forelse ($comics as $comic)
                <div class="col-6 col-md-3 col-lg-2" wire:key="comic-{{ $comic->id }}">

                    {{-- CARD DESAIN BARU --}}
                    <div class="card h-100 border-0 overflow-hidden d-flex flex-column"
                        style="background-color: #242528; border-radius: 12px; transition: transform 0.2s;">

                        <a href="{{ route('comics.show', $comic->slug) }}" class="d-block position-relative">

                            {{-- BADGE RATING (Kiri Atas, Transparan Hitam) --}}
                            <span class="position-absolute top-0 start-0 m-2 px-2 py-1 rounded-pill text-white z-1"
                                style="background: rgba(0, 0, 0, 0.6); font-size: 0.8rem; font-weight: 700; backdrop-filter: blur(2px);">
                                <i
                                    class="bi bi-star-fill text-warning me-1"></i>{{ $comic->combined_score ? number_format($comic->combined_score, 1) : '0.0' }}
                            </span>

                            {{-- GAMBAR COVER --}}
                            @if ($comic->cover_image)
                                <img src="{{ Str::startsWith($comic->cover_image, ['http://', 'https://']) ? $comic->cover_image : asset('storage/' . $comic->cover_image) }}"
                                    class="w-100" style="height:250px; object-fit:cover;" loading="lazy"
                                    alt="{{ $comic->title }}">
                            @else
                                <div class="w-100 d-flex align-items-center justify-content-center bg-secondary text-white"
                                    style="height:250px;">NO COVER</div>
                            @endif
                        </a>

                        {{-- INFO KOMIK BAWAH --}}
                        <div class="p-3 d-flex flex-column flex-grow-1">

                            {{-- JUDUL KOMIK --}}
                            <a href="{{ route('comics.show', $comic->slug) }}"
                                class="text-decoration-none text-white mb-3">
                                <h6 class="fw-bold m-0 text-truncate" style="font-size: 1rem; letter-spacing: 0.3px;">
                                    {{ $comic->title }}
                                </h6>
                            </a>

                            {{-- FOOTER CARD: LIKE KIRI, TAHUN KANAN --}}
                            <div class="mt-auto d-flex justify-content-between align-items-center">

                                {{-- PEMANGGILAN KOMPONEN LIKE BUTTON --}}
                                <livewire:like-button :comic="$comic" :key="'like-' . $comic->id" />

                                {{-- TAHUN RILIS (Tanda '-' di sebelah kanan) --}}
                                <span class="text-secondary fw-semibold" style="font-size: 0.9rem;">
                                    {{ $comic->release_year ?? '-' }}
                                </span>

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-secondary py-5">
                    <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                    Komik tidak ditemukan dengan filter tersebut.
                </div>
            @endforelse
        </div>
    </div>

    {{-- TOMBOL PAGINASI --}}
    <div class="mt-5">
        <div class="d-flex justify-content-center" data-bs-theme="dark">
            {{ $comics->links() }}
        </div>
        @if ($comics->hasPages())
            <div class="text-center text-secondary small mt-2">
                Menampilkan halaman {{ $comics->currentPage() }} dari {{ $comics->lastPage() }}
            </div>
        @endif
    </div>
</div>

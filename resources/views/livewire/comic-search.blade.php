<div>
    {{-- FILTER UI --}}
    <div class="p-3 mb-4 shadow-sm" style="background: #1a1a1a; border-radius: 15px; border: 1px solid #222;">
        <div class="row g-2 align-items-center">

            {{-- Search (Hilangkan .live.debounce agar hanya jalan saat tombol terapkan diklik) --}}
            <div class="col-12 col-md-2">
                <input type="text" wire:model="search" placeholder="Cari komik..."
                    class="form-control filter-input bg-dark text-white border-secondary">
            </div>

            {{-- Dropdown Genre --}}
            <div class="col-6 col-md-2 dropdown">
                {{-- TAMBAHAN: data-bs-auto-close="outside" agar tidak nutup saat checkbox diklik --}}
                <button class="btn btn-dark w-100 dropdown-toggle border-secondary" type="button"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false"
                    style="background-color: #212529; color: #fff;">
                    Genre ({{ count($selectedGenres) }})
                </button>
                <div class="dropdown-menu p-3 bg-dark border-secondary shadow"
                    style="max-height: 300px; overflow-y: auto; width: 250px;">
                    @foreach ($genres as $g)
                        <div class="form-check mb-2">
                            {{-- wire:model biasa (tanpa .live) --}}
                            <input class="form-check-input bg-secondary border-secondary" type="checkbox"
                                wire:model="selectedGenres" value="{{ $g->id }}" id="genre{{ $g->id }}">
                            <label class="form-check-label text-light"
                                for="genre{{ $g->id }}">{{ $g->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-6 col-md-2">
                <select wire:model="filterStatus" class="form-select filter-input border-secondary bg-dark text-white">
                    <option value="">Status</option>
                    @foreach ($statusOptions as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-4 col-md-2">
                <select wire:model="type" class="form-select filter-input bg-dark text-white border-secondary">
                    <option value="">Tipe</option>
                    <option value="Manga">Manga</option>
                    <option value="Manhwa">Manhwa</option>
                    <option value="Manhua">Manhua</option>
                    <option value="One-shot">Oneshot</option>
                </select>
            </div>

            <div class="col-3 col-md-1">
                <input type="number" wire:model="year" placeholder="Tahun"
                    class="form-control filter-input px-2 bg-dark text-white border-secondary">
            </div>

            <div class="col-5 col-md-2">
                <div class="input-group">
                    <select wire:model="sort"
                        class="form-select filter-input border-end-0 bg-dark text-white border-secondary">
                        <option value="latest">Terbaru</option>
                        <option value="popular">Populer</option>
                        <option value="rating">Rating</option>
                        <option value="name">A-Z</option>
                    </select>
                    {{-- Tombol Sort Direction tetap biarkan bereaksi langsung --}}
                    <button wire:click="toggleDirection" class="btn btn-dark border-secondary border-opacity-25"
                        title="Balik Urutan">
                        <i class="bi {{ $sortOrder == 'desc' ? 'bi-sort-down' : 'bi-sort-up-alt' }}"></i>
                    </button>
                </div>
            </div>

            {{-- TOMBOL TERAPKAN DAN RESET --}}
            <div class="col-12 col-md-1 d-flex gap-1">
                <button wire:click="applyFilters" class="btn btn-success w-50" title="Terapkan Filter">
                    <i class="bi bi-check-lg"></i>
                </button>
                <button wire:click="resetFilters" class="btn btn-outline-danger w-50" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
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

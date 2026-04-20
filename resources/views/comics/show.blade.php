@extends('layouts.app')

@section('title', $comic->title . ' - MyComList')

@section('styles')
    <style>
        /* Custom Input untuk Form Tracker */
        .filter-input {
            background-color: #222 !important;
            color: #fff !important;
            border: 1px solid #333 !important;
            border-radius: 12px;
            transition: 0.3s;
        }

        .filter-input:focus {
            border-color: #ff4d00 !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 77, 0, 0.25) !important;
        }

        .badge-orange {
            background-color: rgba(255, 77, 0, 0.1);
            color: #ff4d00;
            border: 1px solid rgba(255, 77, 0, 0.3);
        }

        /* FIX GAMBAR: Tampil utuh tanpa terpotong */
        .cover-wrapper {
            height: 480px;
            background-color: #111;
            border-radius: 20px;
            border: 1px solid #333;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cover-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Style untuk Sinopsis Hide/Show */
        .synopsis-collapsed {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-show-more {
            color: #ff4d00;
            cursor: pointer;
            font-weight: bold;
            font-size: 0.9rem;
            display: block;
            margin-top: 10px;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .cover-wrapper {
                height: 400px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5 mb-5">

        @if (session('success'))
            <div class="alert bg-dark text-success border-0 border-start border-success border-4 shadow mb-4"
                style="border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-5">

            {{-- LEFT: COVER & ACTION --}}
            <div class="col-md-4 col-lg-3">
                <div class="position-sticky" style="top: 20px;">

                    <div class="mb-4 shadow-lg cover-wrapper">
                        @if ($comic->cover_image)
                            {{-- Cek apakah gambarnya adalah Link dari API --}}
                            @if (Str::startsWith($comic->cover_image, ['http://', 'https://']))
                                <img src="{{ $comic->cover_image }}" class="cover-image">
                            @else
                                {{-- Jika bukan link, ambil dari folder lokal --}}
                                <img src="{{ asset('storage/' . $comic->cover_image) }}" class="cover-image">
                            @endif
                        @else
                            <div class="text-secondary" style="font-size: 1.2rem;">NO COVER</div>
                        @endif
                    </div>

                    <livewire:like-button :comic="$comic" />
                </div>
            </div>

            {{-- RIGHT: DETAIL KONTEN --}}
            <div class="col-md-8 col-lg-9">

                {{-- HEADER INFO --}}
                <div class="mb-4 pb-4 border-bottom border-secondary" style="border-opacity: 0.3;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <h1 class="fw-bold mb-2 display-6">{{ $comic->title }}</h1>
                            <p class="fs-5 text-secondary mb-0"><i
                                    class="bi bi-pen-fill me-2"></i>{{ $comic->author ?? 'Unknown Author' }}</p>
                        </div>
                        <div class="rating-box">
                            <i class="bi bi-star-fill text-warning"></i>
                            <span class="fs-4 fw-bold">
                                {{ number_format($comic->avg_score ?? 0, 1) }}
                            </span>
                            <div class="small text-secondary">RATING</div>
                        </div>
                    </div>
                </div>

                {{-- GENRE BADGES --}}
                <div class="mb-5 d-flex gap-2 flex-wrap">
                    <span class="badge badge-orange px-3 py-2 rounded-pill text-uppercase">{{ $comic->type }}</span>
                    @foreach ($comic->genres as $genre)
                        <span
                            class="badge bg-dark border border-secondary text-white-50 px-3 py-2 rounded-pill text-uppercase">{{ $genre->name }}</span>
                    @endforeach
                </div>

                {{-- STATS BOX --}}
                <div class="card-custom p-4 mb-5 shadow border border-dark text-center">
                    <div class="row g-4">
                        <div class="col-4 border-end border-secondary border-opacity-25">
                            <i class="bi bi-activity mb-2 fs-4" style="color: #ff4d00;"></i>
                            <small class="text-secondary d-block text-uppercase mb-1"
                                style="font-size: 0.7rem;">Status</small>
                            <div class="fw-bold text-white">{{ strtoupper(str_replace('-', ' ', $comic->status)) }}</div>
                        </div>
                        <div class="col-4 border-end border-secondary border-opacity-25">
                            <i class="bi bi-calendar-event mb-2 fs-4" style="color: #ff4d00;"></i>
                            <small class="text-secondary d-block text-uppercase mb-1"
                                style="font-size: 0.7rem;">Tahun</small>
                            <div class="fw-bold text-white">{{ $comic->release_year ?? '-' }}</div>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-collection mb-2 fs-4" style="color: #ff4d00;"></i>
                            <small class="text-secondary d-block text-uppercase mb-1"
                                style="font-size: 0.7rem;">Chapter</small>
                            <div class="fw-bold text-white">{{ $comic->total_chapter }}</div>
                        </div>
                    </div>
                </div>

                {{-- SINOPSIS SHOW MORE --}}
                <div class="mb-5">
                    <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-book-half" style="color: #ff4d00;"></i> Sinopsis
                    </h4>
                    <p id="synopsis-text" class="text-white-50 fs-6 synopsis-collapsed"
                        style="line-height: 1.8; text-align: justify;">
                        {{ $comic->synopsis ?? 'Belum ada sinopsis.' }}
                    </p>
                    @if (strlen($comic->synopsis) > 200)
                        <a href="javascript:void(0)" id="toggle-synopsis" class="btn-show-more">Tampilkan
                            Selengkapnya...</a>
                    @endif
                </div>

                {{-- TRACKER WIDGET --}}
                @auth
                    @php $tracked = auth()->user()->trackedComics->where('id', $comic->id)->first(); @endphp
                    <div class="card-custom p-4 p-md-5 shadow-lg border border-dark">
                        <h5 class="fw-bold mb-4 text-white d-flex align-items-center gap-2">
                            <i class="bi bi-bookmark-check-fill" style="color: #ff4d00;"></i> Update Progress
                        </h5>
                        <form action="{{ route('tracker.update', $comic->id) }}" method="POST">
                            @csrf
                            <div class="row g-3 mb-4 text-start">
                                <div class="col-md-4">
                                    <label class="text-secondary small mb-2 fw-semibold">STATUS</label>
                                    <select name="reading_status" class="form-select filter-input py-2">
                                        <option value="plan_to_read"
                                            {{ ($tracked->pivot->reading_status ?? '') == 'plan_to_read' ? 'selected' : '' }}>
                                            Plan to Read</option>
                                        <option value="reading"
                                            {{ ($tracked->pivot->reading_status ?? '') == 'reading' ? 'selected' : '' }}>
                                            Reading</option>
                                        <option value="completed"
                                            {{ ($tracked->pivot->reading_status ?? '') == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="dropped"
                                            {{ ($tracked->pivot->reading_status ?? '') == 'dropped' ? 'selected' : '' }}>
                                            Dropped</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-secondary small mb-2 fw-semibold">CHAPTER</label>
                                    <div class="input-group">
                                        <input type="number" name="last_read_chapter"
                                            class="form-control filter-input py-2 border-end-0"
                                            @if ($comic->total_chapter > 0) max="{{ $comic->total_chapter }}" @endif
                                            min="0" placeholder="0">
                                        {{-- '/' dan logika '?' untuk komik on-going --}}
                                        <span class="input-group-text bg-dark text-secondary border-dark border-start-0 py-2">
                                            / {{ $comic->total_chapter > 0 ? $comic->total_chapter : '?' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-secondary small mb-2 fw-semibold">SKOR (1-10)</label>
                                    <input type="number" name="score" class="form-control filter-input py-2" placeholder="-"
                                        value="{{ $tracked->pivot->score ?? '' }}" max="10" min="1"
                                        step="0.1">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-orange w-100 py-3 fw-bold fs-6 shadow-sm">Simpan
                                Progress</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <script>
        // Toggle Sinopsis
        const toggleBtn = document.getElementById('toggle-synopsis');
        const synopsisText = document.getElementById('synopsis-text');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const isCollapsed = synopsisText.classList.toggle('synopsis-collapsed');
                this.textContent = isCollapsed ? 'Tampilkan Selengkapnya...' : 'Sembunyikan';
            });
        }
    </script>
@endsection

@extends('layouts.app')

@section('title', $comic->title)

@section('content')
<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-4">
            @if($comic->cover_image)
                <img src="{{ asset('storage/' . $comic->cover_image) }}" class="img-fluid rounded shadow" alt="Cover {{ $comic->title }}">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded shadow" style="height: 400px;">
                    <span>No Cover Available</span>
                </div>
            @endif
        </div>

        <div class="col-md-8">
            <h1 class="fw-bold">{{ $comic->title }}</h1>
            <p class="text-muted">Author: {{ $comic->author ?? 'Tidak diketahui' }}</p>

            <div class="mb-3">
                @foreach($comic->genres as $genre)
                    <span class="badge bg-primary me-1">{{ $genre->name }}</span>
                @endforeach
            </div>

            <p><strong>Status:</strong> {{ ucfirst($comic->status) }} | <strong>Total Chapter:</strong> {{ $comic->total_chapter }}</p>

            <hr>
            <h4>Sinopsis</h4>
            <p style="line-height: 1.8;">{{ $comic->synopsis ?? 'Belum ada sinopsis untuk komik ini.' }}</p>

            @auth
                <div class="card mt-4 bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Update My List</h5>

                        @php
                            $tracked = auth()->user()->trackedComics->where('id', $comic->id)->first();
                        @endphp

                        <form action="{{ route('tracker.update', $comic->id) }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label>Status Bacaan</label>
                                    <select name="reading_status" class="form-select" required>
                                        <option value="plan_to_read" {{ ($tracked->pivot->reading_status ?? '') == 'plan_to_read' ? 'selected' : '' }}>Plan to Read</option>
                                        <option value="reading" {{ ($tracked->pivot->reading_status ?? '') == 'reading' ? 'selected' : '' }}>Reading</option>
                                        <option value="completed" {{ ($tracked->pivot->reading_status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="dropped" {{ ($tracked->pivot->reading_status ?? '') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Chapter Terakhir</label>
                                    <input type="number" name="last_read_chapter" class="form-control" value="{{ $tracked->pivot->last_read_chapter ?? 0 }}" min="0" max="{{ $comic->total_chapter }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Skor (1-10)</label>
                                    <input type="number" name="score" class="form-control" value="{{ $tracked->pivot->score ?? '' }}" min="1" max="10" placeholder="Opsional">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Simpan ke My List</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-4">
                    Silakan <a href="{{ route('login') }}" class="alert-link">Login</a> untuk menambahkan komik ini ke daftar bacaanmu (My List).
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Edit Komik')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Edit Komik: {{ $comic->title }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.comics.update', $comic->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Judul Komik</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $comic->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Author / Mangaka</label>
                            <input type="text" name="author" class="form-control" value="{{ old('author', $comic->author) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sinopsis</label>
                            <textarea name="synopsis" class="form-control" rows="6">{{ old('synopsis', $comic->synopsis) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Cover Image (Upload baru untuk mengganti)</label>
                            @if ($comic->cover_image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $comic->cover_image) }}" alt="Current Cover" class="rounded border shadow-sm" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Tipe Komik</label>
                            <select name="type" class="form-select" required>
                                <option value="manga" {{ old('type', $comic->type ?? '') == 'manga' ? 'selected' : '' }}>Manga (Jepang)</option>
                                <option value="manhwa" {{ old('type', $comic->type ?? '') == 'manhwa' ? 'selected' : '' }}>Manhwa (Korea)</option>
                                <option value="manhua" {{ old('type', $comic->type ?? '') == 'manhua' ? 'selected' : '' }}>Manhua (China)</option>
                                <option value="oneshot" {{ old('type', $comic->type ?? '') == 'oneshot' ? 'selected' : '' }}>Oneshot</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="on-going" {{ old('status', $comic->status ?? '') == 'on-going' ? 'selected' : '' }}>On-Going</option>
                                <option value="completed" {{ old('status', $comic->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="dropped" {{ old('status', $comic->status ?? '') == 'dropped' ? 'selected' : '' }}>Dropped (Berhenti)</option>
                                <option value="dikapak" {{ old('status', $comic->status ?? '') == 'dikapak' ? 'selected' : '' }}>Dikapak (Axed)</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Chapter</label>
                                <input type="number" name="total_chapter" class="form-control" value="{{ old('total_chapter', $comic->total_chapter) }}" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Volume</label>
                                <input type="number" name="total_volume" class="form-control" value="{{ old('total_volume', $comic->total_volume ?? '') }}" min="0" placeholder="Unknown">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Rilis</label>
                                <input type="number" name="release_year" class="form-control" placeholder="Contoh: 2023" value="{{ old('release_year', $comic->release_year ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Tamat</label>
                                <input type="number" name="finish_year" class="form-control" placeholder="Kosongkan jika on-going" value="{{ old('finish_year', $comic->finish_year ?? '') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Pilih Genre</label>
                            <div class="border p-2 rounded bg-light" style="max-height: 150px; overflow-y: auto;">
                                @foreach ($genres as $genre)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="genres[]" value="{{ $genre->id }}" id="genre_{{ $genre->id }}" {{ $comic->genres->contains($genre->id) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="genre_{{ $genre->id }}">
                                            {{ $genre->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('admin.comics.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-warning fw-bold">Update Komik</button>
                </div>
            </form>
        </div>
    </div>
@endsection
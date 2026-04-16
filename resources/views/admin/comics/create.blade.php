@extends('layouts.admin')

@section('title', 'Tambah Komik')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Tambah Komik Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.comics.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Judul Komik</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Author / Mangaka</label>
                            <input type="text" name="author" class="form-control" value="{{ old('author') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sinopsis</label>
                            <textarea name="synopsis" class="form-control" rows="5">{{ old('synopsis') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pre-release">Pre-Release</option>
                                <option value="on-going">On-Going</option>
                                <option value="stopped">Stopped</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Chapter Saat Ini</label>
                            <input type="number" name="total_chapter" class="form-control"
                                value="{{ old('total_chapter', 0) }}" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cover Image</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipe Komik</label>
                            <select name="type" class="form-select" required>
                                <option value="manga" {{ old('type', $comic->type ?? '') == 'manga' ? 'selected' : '' }}>
                                    Manga (Jepang)</option>
                                <option value="manhwa" {{ old('type', $comic->type ?? '') == 'manhwa' ? 'selected' : '' }}>
                                    Manhwa (Korea)
                                </option>
                                <option value="manhua" {{ old('type', $comic->type ?? '') == 'manhua' ? 'selected' : '' }}>
                                    Manhua (China)
                                </option>
                                <option value="oneshot"
                                    {{ old('type', $comic->type ?? '') == 'oneshot' ? 'selected' : '' }}>Oneshot</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="on-going"
                                    {{ old('status', $comic->status ?? '') == 'on-going' ? 'selected' : '' }}>On-Going
                                </option>
                                <option value="completed"
                                    {{ old('status', $comic->status ?? '') == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="dropped"
                                    {{ old('status', $comic->status ?? '') == 'dropped' ? 'selected' : '' }}>Dropped
                                    (Berhenti)</option>
                                <option value="dikapak"
                                    {{ old('status', $comic->status ?? '') == 'dikapak' ? 'selected' : '' }}>Dikapak
                                    (Axed)</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Rilis</label>
                                <input type="number" name="release_year" class="form-control" placeholder="Contoh: 2023"
                                    value="{{ old('release_year', $comic->release_year ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Tamat</label>
                                <input type="number" name="finish_year" class="form-control"
                                    placeholder="Kosongkan jika masih on-going"
                                    value="{{ old('finish_year', $comic->finish_year ?? '') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Pilih Genre (Minimal 1)</label>
                            <div class="border p-2 rounded" style="max-height: 150px; overflow-y: auto;">
                                @foreach ($genres as $genre)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="genres[]"
                                            value="{{ $genre->id }}" id="genre_{{ $genre->id }}">
                                        <label class="form-check-label" for="genre_{{ $genre->id }}">
                                            {{ $genre->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <a href="{{ route('admin.comics.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Komik</button>
                </div>
            </form>
        </div>
    </div>
@endsection

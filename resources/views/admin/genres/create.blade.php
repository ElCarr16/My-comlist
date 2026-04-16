@extends('layouts.admin')

@section('title', 'Tambah Genre')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Tambah Genre</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.genres.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Genre</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Action, Slice of Life" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Genre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
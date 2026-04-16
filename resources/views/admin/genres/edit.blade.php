@extends('layouts.admin')

@section('title', 'Edit Genre')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Edit Genre: {{ $genre->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.genres.update', $genre->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Genre</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $genre->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">Update Genre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
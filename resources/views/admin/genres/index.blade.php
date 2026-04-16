@extends('layouts.admin')

@section('title', 'Kelola Genre')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Genre</h2>
    <a href="{{ route('admin.genres.create') }}" class="btn btn-primary">Tambah Genre Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Genre</th>
                    <th>Slug URL</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($genres as $genre)
                <tr>
                    <td>{{ $genre->id }}</td>
                    <td><strong>{{ $genre->name }}</strong></td>
                    <td><code>{{ $genre->slug }}</code></td>
                    <td>
                        <a href="{{ route('admin.genres.edit', $genre->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        
                        <form action="{{ route('admin.genres.destroy', $genre->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus genre ini? Semua relasi di komik juga akan hilang lho!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada data genre.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
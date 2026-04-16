@extends('layouts.admin')

@section('title', 'Kelola Komik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Komik</h2>
    <a href="{{ route('admin.comics.create') }}" class="btn btn-primary">Tambah Komik Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Chapter</th>
                    <th>Genre</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comics as $comic)
                <tr>
                    <td>
                        @if($comic->cover_image)
                            <img src="{{ asset('storage/' . $comic->cover_image) }}" alt="Cover" width="50" class="rounded">
                        @else
                            <span class="text-muted small">No Cover</span>
                        @endif
                    </td>
                    <td><strong>{{ $comic->title }}</strong></td>
                    <td><span class="badge bg-secondary">{{ ucfirst($comic->status) }}</span></td>
                    <td>{{ $comic->total_chapter }}</td>
                    <td>
                        @foreach($comic->genres as $genre)
                            <span class="badge bg-info text-dark">{{ $genre->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.comics.edit', $comic->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.comics.destroy', $comic->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus komik ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data komik.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

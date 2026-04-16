@extends('layouts.admin')

@section('title', 'Kelola Komik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Komik</h2>
    <a href="{{ route('admin.comics.create') }}" class="btn btn-primary">Tambah Komik Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Cover</th>
                        <th>Detail Komik</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Genre</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comics as $comic)
                    <tr>
                        <td>
                            @if($comic->cover_image)
                                <img src="{{ asset('storage/' . $comic->cover_image) }}" alt="Cover" width="60" class="rounded shadow-sm">
                            @else
                                <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 60px; height: 80px; font-size: 10px; border: 1px dashed #ccc;">
                                    No Cover
                                </div>
                            @endif
                        </td>
                        
                        <td>
                            <strong class="d-block fs-5 text-dark">{{ $comic->title }}</strong>
                            <span class="badge bg-secondary text-uppercase">{{ $comic->type }}</span>
                            <small class="text-muted ms-1">
                                ({{ $comic->release_year ?? 'TBA' }} - {{ $comic->finish_year ?? '?' }})
                            </small>
                        </td>
                        
                        <td>
                            @php
                                $badgeClass = [
                                    'on-going' => 'bg-primary',
                                    'completed' => 'bg-success',
                                    'dropped' => 'bg-secondary',
                                    'dikapak' => 'bg-danger'
                                ];
                            @endphp
                            <span class="badge {{ $badgeClass[$comic->status] ?? 'bg-dark' }}">
                                {{ strtoupper(str_replace('-', ' ', $comic->status)) }}
                            </span>
                        </td>
                        
                        <td>
                            <div class="small">
                                <div><span class="text-muted">Vol:</span> <strong>{{ $comic->total_volume ?? '?' }}</strong></div>
                                <div><span class="text-muted">Ch:</span> <strong>{{ $comic->total_chapter ?? '0' }}</strong></div>
                            </div>
                        </td>
                        
                        <td>
                            <div class="d-flex flex-wrap gap-1" style="max-width: 150px;">
                                @foreach($comic->genres as $genre)
                                    <span class="badge bg-info text-dark">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        
                        <td>
                            <a href="{{ route('admin.comics.edit', $comic->id) }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                            
                            <form action="{{ route('admin.comics.destroy', $comic->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus komik ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger mb-1">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data komik.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
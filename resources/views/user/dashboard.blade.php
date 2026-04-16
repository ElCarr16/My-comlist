@extends('layouts.app')

@section('title', 'My Reading List')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Reading List</h2>
        <span style="background: #e2e8f0; padding: 5px 15px; border-radius: 20px; font-size: 14px;">
            Total: <strong>{{ Auth::user()->trackedComics->count() }}</strong> Komik
        </span>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(Auth::user()->trackedComics->isEmpty())
        <div style="text-align: center; padding: 50px; background: white; border-radius: 10px; border: 2px dashed #cbd5e1;">
            <p style="color: #64748b; margin-bottom: 20px;">Daftar bacaanmu masih kosong nih.</p>
            <a href="{{ route('comics.index') }}" style="background: #3b82f6; color: white; padding: 10px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Cari Komik</a>
        </div>
    @else
        <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th style="padding: 15px;">Judul Komik</th>
                        <th style="padding: 15px;">Status</th>
                        <th style="padding: 15px;">Progress</th>
                        <th style="padding: 15px;">Skor</th>
                        <th style="padding: 15px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(Auth::user()->trackedComics as $comic)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 15px;">
                                <a href="{{ route('comics.show', $comic->slug) }}" style="text-decoration: none; color: #1e293b; font-weight: bold;">
                                    {{ $comic->title }}
                                </a>
                            </td>
                            <td style="padding: 15px;">
                                @php
                                    $statusColor = [
                                        'reading' => '#3b82f6',
                                        'completed' => '#10b981',
                                        'plan_to_read' => '#64748b',
                                        'dropped' => '#ef4444'
                                    ];
                                @endphp
                                <span style="color: white; background: {{ $statusColor[$comic->pivot->reading_status] ?? '#64748b' }}; padding: 3px 10px; border-radius: 12px; font-size: 12px;">
                                    {{ str_replace('_', ' ', ucfirst($comic->pivot->reading_status)) }}
                                </span>
                            </td>
                            <td style="padding: 15px;">
                                {{ $comic->pivot->last_read_chapter }} / {{ $comic->total_chapter }}
                            </td>
                            <td style="padding: 15px;">
                                {{ $comic->pivot->score ? '⭐ ' . $comic->pivot->score : '-' }}
                            </td>
                            <td style="padding: 15px;">
                                <a href="{{ route('comics.show', $comic->slug) }}" style="color: #3b82f6; text-decoration: none; font-size: 14px;">Update</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
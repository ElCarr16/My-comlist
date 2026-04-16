@extends('layouts.app')

@section('title', 'Katalog Komik - MyComList')

@section('content')
    <h1 style="margin-bottom: 20px;">Katalog Komik</h1>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        @foreach ($comics as $comic)
            <div
                style="background: white; border: 1px solid #ddd; padding: 15px; border-radius: 8px; width: 250px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; flex-direction: column;">

                @if ($comic->cover_image)
                    <img src="{{ asset('storage/' . $comic->cover_image) }}" alt="Cover {{ $comic->title }}"
                        style="width: 100%; height: 320px; object-fit: cover; border-radius: 6px; margin-bottom: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                @else
                    <div
                        style="width: 100%; height: 320px; background-color: #e2e8f0; border-radius: 6px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-weight: bold; border: 2px dashed #cbd5e1;">
                        NO COVER
                    </div>
                @endif

                <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 18px; line-height: 1.3;">{{ $comic->title }}</h3>

                <div style="margin-bottom: 15px; flex-grow: 1;">

                    <p style="color: #666; font-size: 13px; margin: 0 0 8px 0;">
                        {{ $comic->release_year ?? 'TBA' }}
                        @if ($comic->status == 'completed' || $comic->finish_year)
                            - {{ $comic->finish_year ?? '?' }}
                        @endif
                        • <span style="text-transform: uppercase; font-weight: bold;">{{ $comic->type }}</span>
                    </p>

                    <p style="margin: 0 0 10px 0;">
                        @php
                            $badgeColor = [
                                'on-going' => '#3b82f6', // Biru
                                'completed' => '#10b981', // Hijau
                                'dropped' => '#64748b', // Abu-abu
                                'dikapak' => '#ef4444', // Merah
                            ];
                        @endphp
                        <span
                            style="background: {{ $badgeColor[$comic->status] ?? '#64748b' }}; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
                            {{ strtoupper(str_replace('-', ' ', $comic->status)) }}
                        </span>
                    </p>

                    <div
                        style="background: #f8fafc; padding: 8px; border-radius: 6px; margin-bottom: 12px; font-size: 13px; color: #334155; border: 1px solid #e2e8f0;">
                        📦 Vol: <strong>{{ $comic->total_volume ?? 'Unknown' }}</strong> <br>
                        📖 Ch: <strong>{{ $comic->total_chapter ?? '0' }}</strong>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                        @foreach ($comic->genres as $genre)
                            <span
                                style="background: #e2e8f0; color: #475569; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 500;">
                                {{ $genre->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('comics.show', $comic->slug) }}"
                    style="display: block; text-align: center; background: #007bff; color: white; padding: 10px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: bold; margin-top: auto; transition: background 0.2s;"
                    onmouseover="this.style.backgroundColor='#0056b3'" onmouseout="this.style.backgroundColor='#007bff'">
                    Lihat Detail
                </a>
            </div>
        @endforeach
    </div>
@endsection

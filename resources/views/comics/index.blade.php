@extends('layouts.app')

@section('title', 'Katalog Komik - MyComList')

@section('content')
    <h1>Katalog Komik</h1>

    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        @foreach ($comics as $comic)
            <div
                style="background: white; border: 1px solid #ddd; padding: 15px; border-radius: 8px; width: 250px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0;">{{ $comic->title }}</h3>
                <p style="color: #666; font-size: 14px;">Status: <strong>{{ ucfirst($comic->status) }}</strong></p>

                <div style="margin-bottom: 15px;">
                    @foreach ($comic->genres as $genre)
                        <span
                            style="background: #e2e8f0; color: #475569; padding: 3px 8px; border-radius: 12px; font-size: 12px; margin-right: 5px;">
                            {{ $genre->name }}
                        </span>
                    @endforeach
                </div>

                <a href="{{ route('comics.show', $comic->slug) }}"
                    style="display: inline-block; background: #007bff; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; font-size: 14px;">Lihat
                    Detail</a>
            </div>
        @endforeach
    </div>
@endsection

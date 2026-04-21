@extends('layouts.app')

@section('title', 'Profil Saya - MyComList')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-6">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0 text-white">Profil</h4>

                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-light rounded-pill px-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>

            {{-- ALERT --}}
            @if (session('success'))
                <div class="alert alert-success rounded-pill border-0">
                    {{ session('success') }}
                </div>
            @endif

            {{-- PROFILE CARD --}}
            <div class="card-custom p-4 text-center text-white">

                {{-- AVATAR --}}
                <div class="mb-4">
                    @if ($user->profile_image)
                        {{-- KODE BARU: Menggunakan Storage::url() --}}
                        <img src="{{ Storage::url($user->profile_image) }}" class="rounded-circle"
                            style="width:120px;height:120px;object-fit:cover;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                            style="width:120px;height:120px;background:#222;font-size:40px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- NAME --}}
                <h5 class="fw-bold mb-1">
                    {{ $user->user_name ? '@' . $user->user_name : $user->name }}
                </h5>

                <p class="text-white-50 small mb-4">
                    <i class="bi bi-envelope"></i> {{ $user->email }}
                </p>

                {{-- INFO --}}
                <div class="row text-start mb-4">
                    <div class="col-6">
                        <small class="text-white-50">Role</small>
                        <div class="fw-semibold">{{ ucfirst($user->role ?? 'User') }}</div>
                    </div>

                    <div class="col-6">
                        <small class="text-white-50">Bergabung</small>
                        <div class="fw-semibold">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                {{-- BUTTON --}}
                <a href="{{ route('user.profile.edit') }}" class="btn btn-orange w-100">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profil
                </a>

            </div>
        </div>
    </div>

@endsection

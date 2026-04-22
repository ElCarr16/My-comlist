@extends('layouts.app')

@section('title', 'Lupa Password - MyComList')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-5 col-lg-4 px-4">
        <div class="card-custom p-4 shadow">
            <div class="text-center mb-4">
                <h4 class="fw-bold">Lupa Password?</h4>
                <p class="text-secondary small">Masukkan email untuk menerima kode OTP</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success py-2 small border-0" style="border-radius: 10px;">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 small border-0" style="border-radius: 10px;">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success py-2 small border-0 mb-3" style="border-radius: 10px;">
                    {{ session('status') }}
                    <a href="{{ route('forgot.otp-verify') }}" class="btn btn-sm btn-orange mt-2 w-100 d-block">
                        Verifikasi OTP
                    </a>
                </div>
            @else
                <form action="{{ route('forgot.send-otp') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Email</label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-0 py-2 @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="email@email.com" style="border-radius: 10px;" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-orange w-100 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;" type="submit">
                        <i class="bi bi-envelope me-2"></i> Kirim OTP
                    </button>
                </form>
            @endif

            <div class="text-center mt-4">
                <small class="text-secondary">
                    Ingat password?
                    <a href="{{ route('login') }}" class="text-warning text-decoration-none fw-bold"> Masuk</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

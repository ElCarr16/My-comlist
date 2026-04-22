 @extends('layouts.app')

@section('title', 'Reset Password - MyComList')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-5 col-lg-4 px-4">
        <div class="card-custom p-4 shadow">
            <div class="text-center mb-4">
                <h4 class="fw-bold">Password Baru</h4>
                <p class="text-secondary small">OTP sudah tervalidasi. Buat password baru Anda</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success py-2 small border-0 mb-3" style="border-radius: 10px;">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 small border-0 mb-3" style="border-radius: 10px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('forgot.process-reset') }}" method="POST">
                @csrf
                <input type="hidden" name="otp" value="{{ session('verified_otp') }}">

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Password Baru</label>
                    <div class="position-relative">
                        <input type="password" name="password" class="form-control bg-dark text-white border-0 py-2 @error('password') is-invalid @enderror"
                               placeholder="Password baru (min 8 char)" style="border-radius: 10px;" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Konfirmasi Password</label>
                    <div class="position-relative">
                        <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-0 py-2"
                               placeholder="Konfirmasi password" style="border-radius: 10px;" required>
                    </div>
                </div>

                <button class="btn btn-orange w-100 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;" type="submit">
                    <i class="bi bi-check-circle me-2"></i> Simpan Password Baru
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-secondary">
                    <a href="{{ route('login') }}" class="text-warning text-decoration-none fw-bold">Kembali ke Login</a>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

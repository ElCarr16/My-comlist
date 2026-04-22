@extends('layouts.app')

@section('title', 'Verifikasi OTP - MyComList')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-5 col-lg-4 px-4">
        <div class="card-custom p-4 shadow">
            <div class="text-center mb-4">
                <h4 class="fw-bold">Verifikasi OTP</h4>
                <p class="text-secondary small">Masukkan kode OTP yang dikirim ke email Anda</p>
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

            <form action="{{ route('forgot.process-otp') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ session('reset_email') }}">

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Kode OTP</label>
                    <input type="text" name="otp" maxlength="6" class="form-control bg-dark text-white border-0 py-2 text-center fs-4 fw-bold @error('otp') is-invalid @enderror"
                           style="border-radius: 10px; letter-spacing: 10px;" required autocomplete="one-time-code">
                    @error('otp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Password Baru</label>
                    <div class="position-relative">
                        <input type="password" name="password" class="form-control bg-dark text-white border-0 pe-5 py-2 @error('password') is-invalid @enderror"
                               placeholder="Password baru" style="border-radius: 10px;" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Konfirmasi Password</label>
                    <div class="position-relative">
                        <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-0 pe-5 py-2"
                               placeholder="Konfirmasi password" style="border-radius: 10px;" required>
                    </div>
                </div>

                <button class="btn btn-orange w-100 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;" type="submit">
                    <i class="bi bi-check-circle me-2"></i> Reset Password
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-secondary">
                    <form action="{{ route('forgot.send-otp') }}" method="POST" class="d-inline" id="resendForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('reset_email') }}">
                        <button type="submit" id="resendBtn" class="btn btn-link p-0 m-0 align-baseline text-warning fw-bold text-decoration-none" style="font-size: inherit;">Kirim ulang OTP</button>
                    </form>
                </small>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('resendForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('resendBtn');
        btn.disabled = true; // Matikan tombol
        btn.classList.replace('text-warning', 'text-secondary'); // Ubah warna

        let timeLeft = 60; // Countdown 60 detik
        btn.innerHTML = `Tunggu ${timeLeft}s`;

        const timer = setInterval(() => {
            timeLeft--;
            btn.innerHTML = `Tunggu ${timeLeft}s`;
            if(timeLeft <= 0) {
                clearInterval(timer);
                btn.disabled = false;
                btn.classList.replace('text-secondary', 'text-warning');
                btn.innerHTML = 'Kirim ulang OTP';
            }
        }, 1000);
    });
</script>
@endsection

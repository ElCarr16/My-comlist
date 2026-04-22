@extends('layouts.app')

@section('title', 'Input OTP - MyComList')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-5 col-lg-4 px-4">
        <div class="card-custom p-4 shadow">
            <div class="text-center mb-4">
                <h4 class="fw-bold">Masukkan OTP</h4>
                <p class="text-secondary small">Kode OTP telah dikirim ke <strong>{{ $email }}</strong></p>
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

            <form action="{{ route('forgot.verify-otp') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Kode OTP (6 digit)</label>
                    <input type="text" name="otp" maxlength="6" class="form-control bg-dark text-white text-center fs-3 fw-bold @error('otp') is-invalid @enderror"
                           style="border-radius: 10px; letter-spacing: 8px; font-family: monospace;" required autocomplete="one-time-code">
                    @error('otp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-white-50 mt-1 d-block">OTP berlaku 10 menit</small>
                </div>

                <button class="btn btn-orange w-100 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;" type="submit">
                    <i class="bi bi-arrow-right me-2"></i> Lanjut ke Password Baru
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-secondary">
                    Belum menerima OTP?
                    <form action="{{ route('forgot.send-otp') }}" method="POST" class="d-inline" id="resendForm">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" id="resendBtn" class="btn btn-link p-0 m-0 align-baseline text-warning fw-bold text-decoration-none" style="font-size: inherit;">Kirim ulang</button>
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
        btn.classList.replace('text-warning', 'text-secondary'); // Ubah warna jadi abu-abu

        let timeLeft = 60; // Waktu tunggu 60 detik
        btn.innerHTML = `Tunggu ${timeLeft}s`;

        const timer = setInterval(() => {
            timeLeft--;
            btn.innerHTML = `Tunggu ${timeLeft}s`;
            if(timeLeft <= 0) {
                clearInterval(timer);
                btn.disabled = false;
                btn.classList.replace('text-secondary', 'text-warning');
                btn.innerHTML = 'Kirim ulang';
            }
        }, 1000);
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Login - MyComList')

@section('content')

<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-5 col-lg-4 px-4">

        <div class="card-custom p-4 shadow">

            {{-- TITLE --}}
            <div class="text-center mb-4">
                <h4 class="fw-bold">Masuk</h4>
                <p class="text-secondary small">Lanjutkan koleksi komikmu</p>
            </div>

            {{-- ERROR --}}
            @if($errors->any())
                <div class="alert alert-danger py-2 small border-0" style="border-radius: 10px;">
                    <i class="bi bi-exclamation-circle me-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">
                @csrf

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Email</label>
                    <input type="email" name="email"
                           class="form-control bg-dark text-white border-0 py-2"
                           value="{{ old('email') }}"
                           placeholder="email@email.com"
                           style="border-radius: 10px;"
                           required>
                </div>

                {{-- PASSWORD --}}
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Password</label>

                    {{-- BUNGKUS KHUSUS INPUT & ICON --}}
                    <div class="position-relative">
                        <input type="password" name="password" id="password"
                               class="form-control bg-dark text-white border-0 pe-5 py-2"
                               placeholder="••••••••"
                               style="border-radius: 10px;"
                               required>

                        <button type="button"
                                onclick="togglePassword('password','iconLogin')"
                                class="position-absolute top-50 end-0 translate-middle-y me-3 border-0 bg-transparent text-secondary p-0"
                                style="z-index: 10;">
                            <i id="iconLogin" class="bi bi-eye fs-5"></i>
                        </button>
                    </div>
                </div>

                {{-- BUTTON --}}
                <button class="btn btn-orange w-100 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;">
                    Login
                </button>

            </form>

            {{-- FOOTER --}}
            <div class="text-center mt-4">
                <small class="text-secondary">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-warning text-decoration-none fw-bold">Daftar</a> |
                    <a href="{{ route('forgot-password') }}" class="text-warning text-decoration-none fw-bold">Lupa Password?</a>
                </small>
            </div>

        </div>

    </div>
</div>

{{-- TOGGLE PASSWORD SCRIPT --}}
<script>
function togglePassword(inputId, iconId){
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if(input.type === 'password'){
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

@endsection

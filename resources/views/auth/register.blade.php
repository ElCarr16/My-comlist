@extends('layouts.app')

@section('title', 'Daftar - MyComList')

@section('content')

    <div class="row justify-content-center align-items-center min-vh-100 py-5">
        <div class="col-md-6 col-lg-5 px-4">

            <div class="card-custom p-4 shadow">

                {{-- TITLE --}}
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-uppercase" style="letter-spacing: 1px;">Buat Akun</h4>
                    <p class="text-secondary small">Gabung dan mulai koleksi komikmu</p>
                </div>

                <form action="{{ route('register.process') }}" method="POST">
                    @csrf

                    {{-- USERNAME --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-0 text-secondary"
                                style="border-radius: 10px 0 0 10px;">@</span>
                            <input type="text" name="user_name"
                                class="form-control bg-dark text-white border-0 @error('user_name') is-invalid @enderror"
                                value="{{ old('user_name') }}" placeholder="fajar_otaku"
                                style="border-radius: 0 10px 10px 0;" required>
                        </div>
                        @error('user_name')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- NAME --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Nama
                            Lengkap</label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-0 py-2"
                            style="border-radius: 10px;" value="{{ old('name') }}" placeholder="Fajar ..." required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Email</label>
                        <input type="email" name="email"
                            class="form-control bg-dark text-white border-0 py-2 @error('email') is-invalid @enderror"
                            style="border-radius: 10px;" value="{{ old('email') }}" placeholder="fajar@example.com"
                            required>
                        @error('email')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Password</label>
                        <div class="position-relative">
                            <input type="password" name="password" id="password"
                                class="form-control bg-dark text-white border-0 pe-5 py-2 @error('password') is-invalid @enderror"
                                style="border-radius: 10px;" placeholder="••••••••" required>
                            <button type="button" onclick="togglePassword('password','icon1')"
                                class="position-absolute top-50 end-0 translate-middle-y me-3 border-0 bg-transparent text-secondary p-0">
                                <i id="icon1" class="bi bi-eye fs-5"></i>
                            </button>
                        </div>
                        @error('password')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase" style="letter-spacing: 1px;">Konfirmasi
                            Password</label>
                        <div class="position-relative">
                            <input type="password" name="password_confirmation" id="password2"
                                class="form-control bg-dark text-white border-0 pe-5 py-2" style="border-radius: 10px;"
                                placeholder="••••••••" required>
                            <button type="button" onclick="togglePassword('password2','icon2')"
                                class="position-absolute top-50 end-0 translate-middle-y me-3 border-0 bg-transparent text-secondary p-0">
                                <i id="icon2" class="bi bi-eye fs-5"></i>
                            </button>
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <button class="btn btn-orange w-100 py-2 fw-bold text-uppercase shadow-sm" style="letter-spacing: 1px;">
                        Daftar Akun
                    </button>

                </form>

                {{-- FOOTER --}}
                <div class="text-center mt-4">
                    <small class="text-secondary">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-warning text-decoration-none fw-bold">
                            Login Sekarang
                        </a>
                    </small>
                </div>

            </div>
        </div>
    </div>

    {{-- TOGGLE PASSWORD SCRIPT --}}
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
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

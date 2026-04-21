@extends('layouts.app')

@section('title', 'Edit Profil - MyComList')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-6">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0 text-white">Edit Profil</h4>

                <a href="{{ route('user.profile') }}" class="btn btn-outline-light rounded-pill px-3">
                    Batal
                </a>
            </div>

            <div class="card-custom p-4 text-white">

                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- AVATAR --}}
                    <div class="text-center mb-4">
                        @if ($user->profile_image)
                            <img id="previewImage" src="{{ Storage::url($user->profile_image) }}"
                                class="rounded-circle mb-3" style="width:110px;height:110px;object-fit:cover;">
                        @else
                            <div id="previewPlaceholder"
                                class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width:110px;height:110px;background:#222;font-size:36px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="previewImage" class="rounded-circle mb-3 d-none"
                                style="width:110px;height:110px;object-fit:cover;">
                        @endif

                        <input type="file" name="profile_image" id="imageInput" class="form-control form-control-sm bg-dark text-white border-0"
                            accept="image/*">
                        <small class="text-white-50">Max 2MB (JPG, PNG, WEBP)</small>
                    </div>

                    {{-- USERNAME --}}
                    <div class="mb-3">
                        <label class="form-label text-white">Username</label>

                        <div class="input-group">
                            <span class="input-group-text bg-dark border-0 text-white-50">@</span>

                            <input type="text" name="user_name"
                                class="form-control bg-dark text-white border-0 @error('user_name') is-invalid @enderror"
                                value="{{ old('user_name', $user->user_name) }}" placeholder="user_name" required>
                        </div>

                        @error('user_name')
                            <small class="text-danger">{{ $message }}</small>
                        @else
                            <small class="text-white-50">Username tidak bisa sama dengan pengguna lain.</small>
                        @enderror
                    </div>

                    {{-- NAME --}}
                    <div class="mb-3">
                        <label class="form-label text-white">Nama</label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-0"
                            value="{{ old('name', $user->name) }}" required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-4">
                        <label class="form-label text-white">Email</label>
                        <input type="email" class="form-control bg-secondary text-white border-0"
                            value="{{ $user->email }}" disabled>
                        <small class="text-white-50">Email tidak bisa diubah</small>
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit" class="btn btn-orange w-100">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                    </button>

                </form>

            </div>
        </div>
    </div>

    {{-- IMAGE PREVIEW SCRIPT --}}
    <script>
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.getElementById('previewImage');
                const placeholder = document.getElementById('previewPlaceholder');

                img.src = e.target.result;
                img.classList.remove('d-none');

                if (placeholder) placeholder.style.display = 'none';
            }

            reader.readAsDataURL(file);
        });
    </script>

@endsection

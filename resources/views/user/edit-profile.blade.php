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
                        <div class="position-relative d-inline-block mx-auto mb-3" style="width:110px; height:110px;">

                            {{-- PLACEHOLDER (Huruf F) --}}
                            <div id="previewPlaceholder"
                                class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width:110px; height:110px; background:#222; font-size:36px; position: absolute; top:0; left:0; {{ $user->profile_image ? 'display:none !important;' : '' }}">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            {{-- PREVIEW GAMBAR --}}
                            <img id="previewImage"
                                src="{{ $user->profile_image ? route('profile.image.view', basename($user->profile_image)) : '' }}"
                                class="rounded-circle border border-secondary {{ !$user->profile_image ? 'd-none' : '' }}"
                                style="width:110px; height:110px; object-fit:cover; position: relative; z-index: 1;">

                            {{-- TOMBOL HAPUS --}}
                            <button type="button" id="removeImageBtn"
                                class="btn btn-sm btn-danger rounded-circle position-absolute {{ !$user->profile_image ? 'd-none' : '' }}"
                                style="top:0; right:-5px; z-index: 2;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>

                        {{-- Teks Preview --}}
                        <div id="previewText" class="text-white-50 small mb-2 {{ !$user->profile_image ? 'd-none' : '' }}">
                            Preview Foto Profil
                        </div>

                        <input type="file" name="profile_image" id="imageInput"
                            class="form-control form-control-sm bg-dark text-white border-0 mt-2" accept="image/*">

                        <input type="hidden" name="remove_image" id="removeImageInput" value="0">
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
        const imageInput = document.getElementById('imageInput');
        const previewImage = document.getElementById('previewImage');
        const removeImageBtn = document.getElementById('removeImageBtn');
        const removeImageInput = document.getElementById('removeImageInput');
        const placeholder = document.getElementById('previewPlaceholder');
        const previewText = document.getElementById('previewText'); // Tambahkan ini

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            removeImageInput.value = '0';

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                previewImage.classList.remove('d-none');

                if (placeholder) placeholder.style.display = 'none';
                if (removeImageBtn) removeImageBtn.classList.remove('d-none');
                if (previewText) previewText.classList.remove('d-none'); // Tampilkan teks preview
            }
            reader.readAsDataURL(file);
        });

        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function() {
                removeImageInput.value = '1';
                previewImage.style.display = 'none';
                removeImageBtn.classList.add('d-none');
                if (previewText) previewText.classList.add('d-none'); // Sembunyikan teks
                if (placeholder) placeholder.style.display = 'flex';
            });
        }
    </script>
@endsection

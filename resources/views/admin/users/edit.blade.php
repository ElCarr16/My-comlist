@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Edit Data User: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Password Baru (Kosongkan jika tidak ingin ganti)</label>
                        <div class="input-group">
                            <input type="password" name="password" id="edit_user_password" class="form-control @error('password') is-invalid @enderror">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('edit_user_password', 'eye-icon-edit')">
                                <span id="eye-icon-edit">👁️</span>
                            </button>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <small class="text-muted">Biarkan kosong jika tetap ingin menggunakan password lama.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User Biasa</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.innerText = "🙈"; 
        } else {
            passwordInput.type = "password";
            eyeIcon.innerText = "👁️"; 
        }
    }
</script>
@endsection
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            background: #212529;
            color: white;
            padding-top: 20px;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .nav-link {
            color: #adb5bd;
            margin: 5px 15px;
            border-radius: 5px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #343a40;
            color: white;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4 class="text-center mb-4">MyComList Admin</h4>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                href="{{ route('admin.users.index') }}">Kelola User</a>
            <a class="nav-link {{ request()->is('admin/genres*') ? 'active' : '' }}"
                href="{{ route('admin.genres.index') }}">Kelola Genre</a>
            <a class="nav-link {{ request()->is('admin/comics*') ? 'active' : '' }}"
                href="{{ route('admin.comics.index') }}">Kelola Komik</a>
            <hr>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">Logout</button>
            </form>
        </nav>
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (!passwordInput || !eyeIcon) return;

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerText = "🙈";
            } else {
                passwordInput.type = "password";
                eyeIcon.innerText = "👁️";
            }
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyComList')</title>
</head>

<body style="font-family: sans-serif; margin: 0; padding: 0; background-color: #f9f9f9;">

    <nav
        style="background-color: #333; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">

        <div style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ url('/') }}"
                style="color: white; text-decoration: none; font-weight: bold; font-size: 22px; margin-right: 15px;">MyComList</a>

            <a href="{{ route('comics.index') }}"
                style="color: #ddd; text-decoration: none; font-size: 16px; transition: color 0.3s;"
                onmouseover="this.style.color='white'" onmouseout="this.style.color='#ddd'">Katalog</a>

            @auth
                <a href="{{ route('user.dashboard') }}"
                    style="color: #ddd; text-decoration: none; font-size: 16px; transition: color 0.3s;"
                    onmouseover="this.style.color='white'" onmouseout="this.style.color='#ddd'">My List</a>
            @endauth
        </div>

        <div style="display: flex; align-items: center; gap: 20px;">
            @guest
                <a href="{{ route('login') }}" style="color: white; text-decoration: none; font-size: 16px;">Login</a>
                <a href="{{ route('register') }}"
                    style="background-color: #007bff; color: white; padding: 8px 15px; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: bold;">Daftar</a>
            @else
                <span style="color: #aaa; font-size: 14px;">Halo, <strong>{{ Auth::user()->name }}</strong></span>

                <a href="#" style="color: #ddd; text-decoration: none; font-size: 16px; transition: color 0.3s;"
                    onmouseover="this.style.color='white'" onmouseout="this.style.color='#ddd'">Profil</a>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit"
                        style="background: none; border: none; color: #ff4d4d; cursor: pointer; font-size: 16px; padding: 0; font-weight: bold;"
                        onmouseover="this.style.color='#ff1a1a'" onmouseout="this.style.color='#ff4d4d'">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <main style="padding: 20px;">
        @yield('content')
    </main>
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

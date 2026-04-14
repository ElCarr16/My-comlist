<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyComList')</title>
</head>

<body style="font-family: sans-serif; margin: 0; padding: 0; background-color: #f9f9f9;">

    <nav style="background-color: #333; padding: 15px; color: white;">
        <a href="{{ route('comics.index') }}"
            style="color: white; text-decoration: none; font-weight: bold; font-size: 20px;">MyComList</a>
    </nav>

    <main style="padding: 20px;">
        @yield('content')
    </main>

</body>

</html>

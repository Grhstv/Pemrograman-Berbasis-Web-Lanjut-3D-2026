<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Warung Kita' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Warung Kita. Semua hak dilindungi.</p>
    </footer>

</body>

</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', 'Perpustakaan Digital')</title>

    <!-- Hubungkan CSS melalui Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Header & Navbar -->
    <header class="main-header">
        <div class="container header-content">
            <h1 class="logo">📚 PustakaDigital</h1>
            <nav class="main-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Daftar Buku</a>
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="container main-content">
        @yield('konten')
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2026 Perpustakaan Digital — NIM: 250441100078. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
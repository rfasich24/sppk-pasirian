<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPPK Pasirian')</title>
    <!-- Anda bisa menyisipkan CDN Tailwind CSS/Bootstrap di sini nanti -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-900 font-sans">

    <!-- Navbar Sederhana -->
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-xl font-bold">SPPK Pasirian</a>
            <div class="space-x-4">
                <a href="#" class="hover:underline">Dashboard</a>
                <a href="#" class="hover:underline">Validasi Data</a>
                <a href="#" class="hover:underline">Matriks Saaty</a>
                <a href="#" class="hover:underline">Rekomendasi</a>
            </div>
        </div>
    </nav>

    <!-- Konten Utama Dinamis -->
    <main class="container mx-auto mt-8 px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white text-center p-4 mt-12 border-t text-sm text-gray-500">
        &copy; {{ date('Y') }} SPPK Pasirian - Laravel Version.
    </footer>

</body>

</html>

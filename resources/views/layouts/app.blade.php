<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BANPRI</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome CDN -->
    <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js"></script>

</head>

<body class="bg-blue-50 flex">
    <!-- Sidebar -->
    <aside class="w-64 min-h-screen bg-white shadow-lg p-6 fixed left-0 top-0 bottom-0">
        <h1 class="text-4xl font-bold text-gray-900 mb-6">BANPRI<span class="text-green-500">.</span></h1>
        <p class="text-gray-400 text-sm mb-6">SPK Bantuan Benih Tanaman Pangan</p>
        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg 
                   {{ request()->is('dashboard') ? 'bg-green-100 text-green-600 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('kriteria.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg 
                   {{ request()->is('kriteria*') ? 'bg-green-100 text-green-600 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-list"></i>
                <span>Kriteria</span>
            </a>
            <a href="{{ route('kelompok-tani.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg 
                   {{ request()->is('kelompok-tani*') ? 'bg-green-100 text-green-600 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-users"></i>
                <span>Data Kelompok Tani</span>
            </a>
            <a href="{{ route('hasil-seleksi.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg 
                   {{ request()->is('hasil-seleksi*') ? 'bg-green-100 text-green-600 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-chart-bar"></i>
                <span>Hasil Seleksi</span>
            </a>
            <a href="{{ route('laporan.index') }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg 
                   {{ request()->is('laporan*') ? 'bg-green-100 text-green-600 font-semibold' : 'text-gray-700 hover:bg-gray-100 hover:text-green-600' }}">
                <i class="fas fa-file-alt"></i>
                <span>Laporan</span>
            </a>
        </nav>
    </aside>

    <!-- Kontainer Utama -->
    <div class="flex-1 flex flex-col min-h-screen ml-64">
        <!-- Header -->
        <header class="bg-blue-50 p-4 flex items-center justify-between shadow-sm fixed top-0 left-64 right-0 z-50">
            <!-- Search Bar -->
            <div class="relative flex-1 max-w-xl">
                <input type="text" placeholder="Search here" class="w-full p-3 pl-12 bg-white border border-gray-300 rounded-full shadow-sm 
           focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Right Side Icons -->
            <div class="flex items-center space-x-6 relative">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo 1" class="h-10">
                <img src="{{ asset('images/logo2.png') }}" alt="Logo 2" class="h-10">

                <!-- User Info with Dropdown -->
                <div class="relative">
                    <div class="flex items-center space-x-3 cursor-pointer" id="profile-dropdown-toggle">
                        <span class="text-gray-700">Hello, <strong>{{ Auth::user()->name }}</strong></span>
                        <div
                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>

                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown"
                        class="absolute top-full right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg hidden z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-100">
                            Profile
                        </a>
                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <script>
        // Dropdown toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.getElementById('profile-dropdown-toggle');
            const dropdownMenu = document.getElementById('profile-dropdown');

            dropdownToggle.addEventListener('click', (event) => {
                event.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (event) => {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        });
        </script>

        <!-- Konten yang Bisa di-Scroll -->
        <main class="p-6 flex-1 overflow-auto h-[calc(100vh-4rem)] mt-20">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-blue-500 text-white text-center py-6 shadow-inner">
            <div class="container mx-auto">
                <p class="text-sm">&copy; 2025 <strong>BANPRI</strong> - Sistem Pendukung Keputusan Bantuan Benih
                    Tanaman Pangan.</p>
                <p class="text-xs mt-1">Dinas Pertanian Kabupaten Buleleng</p>
                <div class="mt-2 flex justify-center space-x-4">
                    <a href="#" class="text-white hover:text-gray-700 transition duration-300">Tentang</a>
                    <span>|</span>
                    <a href="#" class="text-white hover:text-gray-700 transition duration-300">Kebijakan Privasi</a>
                    <span>|</span>
                    <a href="#" class="text-white hover:text-gray-700 transition duration-300">Kontak</a>
                </div>
            </div>
        </footer>

    </div>
    @stack('scripts')
</body>

</html>
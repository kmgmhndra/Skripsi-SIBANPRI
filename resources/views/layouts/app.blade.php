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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">



</head>

<body class="bg-blue-50 flex">
    <!-- Sidebar -->
    <aside class="hidden md:flex flex-col w-64 min-h-screen bg-white shadow-lg p-6 fixed left-0 top-0 bottom-0">
        <h1 class="text-4xl font-bold text-gray-900 mb-6 cursor-pointer" onclick="window.location.href='/dashboard'">
            SIBANPRI<span class="text-green-500">.</span></h1>
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
            <a id="selectedKecamatanRequest"
                href="{{ route('hasil-seleksi.index', ['kecamatan_id' => $kecamatanId ?? 1]) }}"
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
        <header
            class="hidden md:flex bg-blue-50 p-4 items-center justify-between shadow-sm fixed top-0 left-64 right-0 z-50">
            <!-- Search Bar -->
            <div class="hidden md:flex relative flex-1 max-w-xl">
                <!-- Ikon Search -->
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z" />
                </svg>

                <!-- Input Search -->
                <input id="searchInput" type="text" placeholder="Search here"
                    class="w-full p-3 pl-12 bg-white border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none">
            </div>

            <script>
                document.getElementById('searchInput').addEventListener('keypress', function (event) {
                    if (event.key === 'Enter') { // Jalankan pencarian saat tekan Enter
                        let text = this.value;
                        if (text) {
                            window.find(text);
                        }
                    }
                });
            </script>

            <!-- Right Side Icons -->
            <div class="hidden md:flex items-center space-x-6 relative">

                <!-- Logo 1 -->
                <img src="{{ asset('images/logo1.png') }}" alt="Logo 1" class="h-10">

                <!-- Logo 2 -->
                <img src="{{ asset('images/logo2.png') }}" alt="Logo 2" class="h-10">

                <!-- Dropdown Tahun -->
                <div class="relative w-20 group">
                    <select
                        class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 pl-2 pr-6 text-center rounded-2xl shadow-sm focus:outline-none focus:border-blue-500 w-full cursor-pointer"
                        name="tahun" id="tahun-dropdown" onchange="location = this.value;" title="Pilih Tahun">

                        @php
                            $tahunSekarang = date('Y');
                            $tahunSession = session('tahun', $tahunSekarang);
                        @endphp

                        @for ($tahun = $tahunSekarang - 4; $tahun <= $tahunSekarang; $tahun++)
                            <option class="mr-4"  value="{{ route('setTahun', ['tahun' => $tahun]) }}"
                            {{ $tahunSession == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endfor

                    </select>

                    <!-- Icon Dropdown -->
                    <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                <!-- User Icon with Dropdown -->
                <div class="relative">
                    <div class="cursor-pointer" id="profile-dropdown-toggle">
                        <div
                            class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center text-gray-700 font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>

                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown"
                        class="absolute top-full right-0 mt-2 w-48 bg-white border border-gray-300 rounded-xl shadow-lg hidden z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-100">
                            Profile
                        </a>
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

        <!-- Header Mobile -->
        <header
            class="md:hidden bg-blue-50 p-4 shadow-sm fixed top-0 left-0 right-0 z-50 flex items-center justify-between">

            <!-- Hamburger Button -->
            <div class="flex items-center">
                <button id="menu-btn" class="text-gray-700 z-50">
                    <!-- Hamburger Icon -->
                    <svg id="hamburger-icon" xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                        fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                    </svg>
                    <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                        class="bi bi-x-lg hidden" viewBox="0 0 16 16">
                        <path
                            d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                    </svg>
                </button>
            </div>

            <!-- Logo di Tengah -->
            <div class="flex items-center space-x-2 pl-8">
                <img src="{{ asset('images/logo1.png') }}" alt="Logo 1" class="w-10 h-10">
                <img src="{{ asset('images/logo2.png') }}" alt="Logo 2" class="w-10 h-10">
            </div>

            <!-- Dropdown Tahun di Kanan -->
            <div class="relative w-20">
                <select
                    class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 pl-2 pr-6 text-center rounded-2xl shadow-sm focus:outline-none focus:border-blue-500 w-full cursor-pointer"
                    name="tahun" id="tahun-dropdown" onchange="location = this.value;" title="Pilih Tahun">

                    @for ($tahun = 2024; $tahun <= date('Y'); $tahun++)
                        <option value="{{ url()->current() }}?tahun={{ $tahun }}" {{ request('tahun', date('Y')) == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endfor
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

        </header>


        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="fixed top-0 left-0 w-full h-max bg-white shadow-lg transform -translate-y-full transition-transform duration-300 ease-in-out md:hidden z-40">
            <div class="flex flex-col py-8 space-y-1 ml-10 mt-16">
                <a href="{{ route('dashboard') }}"
                    class="block py-2 text-gray-800 hover:text-green-500 pr-8">Beranda</a>
                <a href="{{ route('kriteria.index') }}"
                    class="block py-2 text-gray-800 hover:text-green-500 pr-8">Kriteria</a>
                <a href="{{ route('kelompok-tani.index') }}"
                    class="block py-2 text-gray-800 hover:text-green-500 pr-8">Data Kelompok Tani</a>
                <a href="{{ route('hasil-seleksi.index') }}"
                    class="block py-2 text-gray-800 hover:text-green-500 pr-8">Hasil Seleksi</a>
                <a href="{{ route('laporan.index') }}"
                    class="block py-2 text-gray-800 hover:text-green-500 pr-8">Laporan</a>
                <a href="{{ route('profile.edit') }}"
                    class="block py-2 text-gray-800 hover:text-green-500 pr-8">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left py-2 text-gray-800 hover:text-green-500 pr-8">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <script>
            const menuBtn = document.getElementById("menu-btn");
            const mobileMenu = document.getElementById("mobile-menu");
            const hamburgerIcon = document.getElementById("hamburger-icon");
            const closeIcon = document.getElementById("close-icon");

            // Toggle menu visibility and icon change
            menuBtn.addEventListener("click", () => {
                if (mobileMenu.classList.contains("-translate-y-full")) {
                    mobileMenu.classList.remove("-translate-y-full"); // Show menu
                    hamburgerIcon.classList.add("hidden"); // Hide hamburger icon
                    closeIcon.classList.remove("hidden"); // Show close (X) icon
                } else {
                    mobileMenu.classList.add("-translate-y-full"); // Hide menu
                    hamburgerIcon.classList.remove("hidden"); // Show hamburger icon
                    closeIcon.classList.add("hidden"); // Hide close (X) icon
                }
            });

            // Close the menu if clicked outside the menu or button
            window.addEventListener("click", (e) => {
                if (!menuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add("-translate-y-full");
                    hamburgerIcon.classList.remove("hidden");
                    closeIcon.classList.add("hidden");
                }
            });
        </script>


        </header>

        <script>
            // Dropdown toggle functionality
            document.addEventListener('DOMContentLoaded', function () {
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
        <main class="p-6 flex-1 overflow-auto h-[calc(100vh-4rem)] mt-20 md:ml-0 -ml-64">

            @yield('content')
        </main>

        <!-- Footer -->

        <footer class="bg-blue-900 text-white py-8 px-4 md:px-8 lg:px-12 md:ml-0 -ml-64">
            <!-- Garis atas -->
            <div class="border-t border-slate-500 mb-4"></div>

            <div
                class="container mx-auto flex flex-col md:flex-row justify-between items-start md:items-center space-y-6 md:space-y-0">
                <div>
                    <h1 class="text-4xl font-bold cursor-pointer" onclick="window.location.href='/dashboard'">
                        BANPRI<span class="text-green-400">.</span></h1>
                </div>
                <div class="flex flex-col md:flex-row md:space-x-8">
                    <ul class="space-y-1">
                        <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
                        <li><a href="{{ route('kriteria.index') }}" class="hover:underline">Kriteria</a></li>
                        <li><a href="{{ route('kelompok-tani.index') }}" class="hover:underline">Data Kelompok Tani</a>
                        </li>
                    </ul>
                    <ul class="space-y-1">
                        <li><a href="{{ route('hasil-seleksi.index') }}" class="hover:underline">Hasil Seleksi</a></li>
                        <li><a href="{{ route('laporan.index') }}" class="hover:underline">Laporan</a></li>
                    </ul>
                </div>
                <div class="text-sm">
                    <p class="flex items-center"><span class="mr-2">📍</span>Jl. A. Yani No.99, Kaliuntu, Kec. Buleleng,
                        Kabupaten Buleleng, Bali 81116</p>
                    <p class="flex items-center"><span class="mr-2">📞</span> (0362) 25090</p>
                    <p class="flex items-center"><span class="mr-2">✉️</span> distan@bulelengkab.go.id</p>
                    <div class="mt-2 ml-0.5 flex space-x-4">
                        <!-- YouTube -->
                        <a href="http://www.youtube.com/@dinaspertanianbuleleng7447" class="hover:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-youtube" viewBox="0 0 16 16">
                                <path
                                    d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z" />
                            </svg>
                        </a>

                        <!-- Instagram -->
                        <a href="https://www.instagram.com/distanbuleleng/" class="hover:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-instagram" viewBox="0 0 16 16">
                                <path
                                    d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                            </svg>
                        </a>

                        <!-- Website -->
                        <a href="https://distan.bulelengkab.go.id/" class="hover:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-globe" viewBox="0 0 16 16">
                                <path
                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855A8 8 0 0 0 5.145 4H7.5zM4.09 4a9.3 9.3 0 0 1 .64-1.539 7 7 0 0 1 .597-.933A7.03 7.03 0 0 0 2.255 4zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a7 7 0 0 0-.656 2.5zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5zM8.5 5v2.5h2.99a12.5 12.5 0 0 0-.337-2.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5zM5.145 12q.208.58.468 1.068c.552 1.035 1.218 1.65 1.887 1.855V12zm.182 2.472a7 7 0 0 1-.597-.933A9.3 9.3 0 0 1 4.09 12H2.255a7 7 0 0 0 3.072 2.472M3.82 11a13.7 13.7 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm6.853 3.472A7 7 0 0 0 13.745 12H11.91a9.3 9.3 0 0 1-.64 1.539 7 7 0 0 1-.597.933M8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855q.26-.487.468-1.068zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.7 13.7 0 0 1-.312 2.5m2.802-3.5a7 7 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7 7 0 0 0-3.072-2.472c.218.284.418.598.597.933M10.855 4a8 8 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4z" />
                            </svg>
                        </a>
                    </div>

                </div>
            </div>

            <!-- Garis bawah -->
            <div class="text-center mt-4 border-t border-slate-500 pt-3 text-sm -mb-5">
                <p>Copyright © 2025 • All Rights Reserved</p>
            </div>
        </footer>




    </div>
    @stack('scripts')
</body>

</html>
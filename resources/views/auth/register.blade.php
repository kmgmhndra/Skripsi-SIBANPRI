<x-guest-layout>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - BANPRI</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
    </head>

    <body class="bg-blue-50 flex items-center justify-center min-h-screen relative">
        <!-- Logo Section -->
        <div class="absolute top-4 right-4 flex space-x-4">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo Pemerintah" class="h-16">
            <img src="{{ asset('images/logo2.png') }}" alt="Logo Distan" class="h-16">
        </div>

        <div class="max-w-6xl w-full mx-auto flex flex-col md:flex-row items-center">
            <!-- Left Section (Illustrations and Text) -->
            <div class="w-full md:w-3/5 px-6 md:px-12 py-8 flex flex-col justify-center items-start text-center md:text-left">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-800 leading-tight">Join</h1>
                <h1 class="text-6xl md:text-7xl font-bold text-black">SIBANPRI<span class="text-green-600">.</span></h1>
                <p class="text-gray-600 mt-4 text-lg md:text-xl">
                    Sistem Pendukung Keputusan Pemilihan Prioritas Penerima Bantuan Benih Tanaman Pangan<br>
                    <strong>(Dinas Kabupaten Buleleng)</strong>
                </p>
                <div class="mt-8">
                    <img src="{{ asset('images/login.png') }}" alt="Illustration"
                        class="w-full max-w-3xl md:max-w-4xl mx-auto md:mx-0">
                </div>
            </div>

            <!-- Right Section (Register Form) -->
            <div class="w-full md:w-2/5 px-6 md:px-12 py-10 bg-white shadow-xl rounded-lg flex flex-col justify-center">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center">Daftar</h2>
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Full Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input id="name" type="text" name="name" required autofocus
                            class="mt-2 block w-full rounded-md border border-gray-300 shadow-lg focus:ring-blue-500 focus:border-blue-500 hover:border-blue-600 p-3 transition">
                        @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" type="email" name="email" required
                            class="mt-2 block w-full rounded-md border border-gray-300 shadow-lg focus:ring-blue-500 focus:border-blue-500 hover:border-blue-600 p-3 transition">
                        @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                class="mt-2 block w-full rounded-md border border-gray-300 shadow-lg focus:ring-blue-500 focus:border-blue-500 hover:border-blue-600 p-3 transition pr-10">
                            <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" onclick="togglePassword()">
                                <i id="eyeIcon" class="fas fa-eye text-gray-500"></i>
                            </span>
                        </div>
                        @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password Input -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="mt-2 block w-full rounded-md border border-gray-300 shadow-lg focus:ring-blue-500 focus:border-blue-500 hover:border-blue-600 p-3 transition">
                        @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col space-y-4">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md text-sm md:text-base">
                            Daftar
                        </button>
                    </div>
                </form>

                <!-- Already Registered Link -->
                <div class="text-center mt-6">
                    <p class="text-gray-600">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Masuk di sini</a></p>
                </div>
            </div>
        </div>

        <!-- Password Visibility Toggle Script -->
        <script>
            function togglePassword() {
                const passwordField = document.getElementById('password');
                const eyeIcon = document.getElementById('eyeIcon');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            }
        </script>
    </body>
</x-guest-layout>

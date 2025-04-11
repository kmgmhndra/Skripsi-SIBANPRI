<x-guest-layout>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to BANPRI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
</head>

<body class="bg-gradient-to-r from-blue-50 to-blue-100 flex items-center justify-center min-h-screen relative">
    <!-- Logo Section -->
    <div class="hidden md:flex absolute top-4 right-4 space-x-4">
        <img src="{{ asset('images/logo1.png') }}" alt="Logo Pemerintah" class="h-12 md:h-16 hover:scale-105 transition-transform duration-300">
        <img src="{{ asset('images/logo2.png') }}" alt="Logo Distan" class="h-12 md:h-16 hover:scale-105 transition-transform duration-300">
    </div>
     <!-- Mobile Logo -->
     <div class="md:hidden flex justify-center space-x-4 mt-4">
        <img src="{{ asset('images/logo1.png') }}" alt="Logo Pemerintah" class="h-12 hover:scale-105 transition-transform duration-300">
        <img src="{{ asset('images/logo2.png') }}" alt="Logo Distan" class="h-12 hover:scale-105 transition-transform duration-300">
    </div>

    <div class="max-w-6xl w-full mx-auto flex flex-col md:flex-row items-center px-4">
        <!-- Left Section -->
        <div class="w-full md:w-3/5 px-2 md:px-12 py-8 flex flex-col justify-center items-center md:items-start text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight">Welcome to</h1>
            <h1 class="text-5xl md:text-7xl font-bold text-black">SIBANPRI<span class="text-green-600">.</span></h1>
            <p class="text-gray-600 mt-4 text-base md:text-lg">
                Sistem Pendukung Keputusan Pemilihan Prioritas Penerima Bantuan Benih Tanaman Pangan<br>
                <strong>(Dinas Pertanian Kabupaten Buleleng)</strong>
            </p>
            <div class="mt-6 md:mt-8">
                <img src="{{ asset('images/login.png') }}" alt="Illustration"
                    class="w-4/5 md:w-full max-w-3xl md:max-w-4xl mx-auto md:mx-0 hover:scale-105 transition-transform duration-300">
            </div>
        </div>

        <!-- Right Section (Login Form) -->
        <div class="w-full md:w-2/5 px-4 md:px-12 py-8 md:py-10 bg-white shadow-2xl rounded-lg flex flex-col justify-center transform hover:scale-105 transition-transform duration-300 mt-6 md:mt-0">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center">Login</h2>
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" required
                        class="mt-2 block w-full rounded-md border border-gray-300 shadow-lg focus:ring-blue-500 focus:border-blue-500 hover:border-blue-600 p-3 transition duration-300">
                    @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="mt-2 block w-full rounded-md border border-gray-300 shadow-lg focus:ring-blue-500 focus:border-blue-500 hover:border-blue-600 p-3 transition duration-300 pr-10">
                        <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" onclick="togglePassword()">
                            <i id="eyeIcon" class="fas fa-eye text-gray-500 hover:text-blue-600 transition duration-300"></i>
                        </span>
                    </div>
                    @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember me</label>
                </div>

                <div class="flex flex-col space-y-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md transition duration-300">
                        Login
                    </button>
                    @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:underline text-center" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>
</x-guest-layout>

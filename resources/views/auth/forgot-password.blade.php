<x-guest-layout>
    <div class="bg-blue-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-800 text-center">Reset Your Password</h1>
                <p class="text-gray-600 mt-2 text-center">
                    Enter your email address below, and we’ll send you a link to reset your password.
                </p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="mt-6">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-6">
                        <x-input-label for="email" :value="__('Email Address')" class="mb-3" />
                        <x-text-input id="email" class="block w-full border border-gray-400 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"
                                      type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <x-primary-button class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded-md pl-24">
                            {{ __('Send Password Reset Link') }}
                        </x-primary-button>
                    </div>
                </form>

                <!-- Back to Login Button -->
                <div class="flex justify-center mt-4">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">
                        &larr; Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
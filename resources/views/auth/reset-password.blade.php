<x-guest-layout>
    <div class="bg-blue-50 min-h-screen flex items-center justify-center">
        <div class="max-w-4xl w-full mx-auto bg-white rounded-2xl shadow-lg flex overflow-hidden">
            <!-- Left Section -->
            <div class="w-1/2 p-8 flex flex-col justify-center">
                <h1 class="text-4xl font-bold text-gray-800">Reset Your Password</h1>
                <p class="text-gray-600 mt-4 text-sm">
                    Enter your new password below to reset your account access and get back to the system.
                </p>
                <img src="{{ asset('images/login.png') }}" alt="Illustration" class="mt-8">
            </div>
            <!-- Right Section -->
            <div class="w-1/2 bg-blue-100 p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Reset Password</h2>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700">
                            {{ __('Reset Password') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

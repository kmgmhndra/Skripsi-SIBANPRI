<x-guest-layout>
    <div class="bg-blue-50 min-h-screen flex items-center justify-center">
        <div class="max-w-4xl w-full mx-auto bg-white rounded-2xl shadow-lg flex overflow-hidden">
            <!-- Left Section -->
            <div class="w-1/2 p-8 flex flex-col justify-center">
                <h1 class="text-4xl font-bold text-gray-800">Password Confirmation</h1>
                <p class="text-gray-600 mt-4 text-sm">
                    This is a secure area of the application. Please confirm your password before continuing.
                </p>
                <img src="{{ asset('images/confirm-password-illustration.svg') }}" alt="Illustration" class="mt-8">
            </div>
            <!-- Right Section -->
            <div class="w-1/2 bg-blue-100 p-8 flex flex-col justify-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Confirm Password</h2>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-3 bg-blue-600 hover:bg-blue-700">
                            {{ __('Confirm') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

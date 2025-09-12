<x-guest-layout>
    <!-- Page Heading -->
    <h2 class="text-3xl font-extrabold text-center mb-8 text-blue-800 tracking-wide">
        Confirm Password
    </h2>

    <!-- Description -->
    <p class="mb-6 text-center text-gray-600 text-lg">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-black font-semibold text-lg" />
            <x-text-input id="password"
                          class="block mt-1 w-full rounded-lg border border-gray-400 bg-white/80
                                 text-lg placeholder:text-gray-500
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="password"
                          name="password"
                          required
                          autocomplete="current-password"
                          placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

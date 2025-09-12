<x-guest-layout>
    <!-- Page Heading -->
    <h2 class="text-3xl font-extrabold text-center mb-8 text-blue-800 tracking-wide">
        Forgot Password
    </h2>

    <!-- Description -->
    <p class="mb-6 text-center text-gray-600 text-lg">
        {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center text-green-600" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-black font-semibold text-lg" />
            <x-text-input id="email"
                          class="block mt-1 w-full rounded-lg border border-gray-400 bg-white/80
                                 text-lg placeholder:text-gray-500
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autofocus
                          placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg text-lg w-full sm:w-auto">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>

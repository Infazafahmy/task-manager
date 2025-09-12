<x-guest-layout>
    <!-- Page Heading -->
    <h2 class="text-3xl font-extrabold text-center mb-8 text-blue-800 tracking-wide">
        Reset Password
    </h2>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center text-green-600" :status="session('status')" />

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-black font-semibold text-lg" />
            <x-text-input id="email"
                          class="block mt-1 w-full rounded-lg border border-gray-400 bg-white/80
                                 text-lg placeholder:text-gray-500
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="email"
                          name="email"
                          :value="old('email', $request->email)"
                          required
                          autofocus
                          autocomplete="username"
                          placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
        </div>

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
                          autocomplete="new-password"
                          placeholder="Enter your new password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-black font-semibold text-lg" />
            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full rounded-lg border border-gray-400 bg-white/80
                                 text-lg placeholder:text-gray-500
                                 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password"
                          placeholder="Confirm your new password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg text-lg w-full sm:w-auto">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
